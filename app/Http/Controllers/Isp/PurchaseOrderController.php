<?php

namespace App\Http\Controllers\Isp;

use App\Exports\IspPurchaseOrderExport;
use App\Http\Controllers\Controller;
use App\Models\CoverageRequest;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = PurchaseOrder::with(['areaInfo'])
            ->where('purchase_orders.user_id', $user->user_id)
            ->leftJoin('orders', \Illuminate\Support\Facades\DB::raw("COALESCE(NULLIF(purchase_orders.admin_no_order_input, ''), purchase_orders.admin_no_order)"), '=', 'orders.no_order')
            ->leftJoin('isps', 'purchase_orders.brand', '=', 'isps.isp_brand')
            ->leftJoin('areas', 'purchase_orders.sto', '=', 'areas.sto')
            ->select(
                'purchase_orders.*',
                'orders.status_order as order_status_val',
                'orders.keterangan as order_keterangan_val',
                'isps.isp_name as joined_isp_name',
                'areas.branch as joined_branch',
                'areas.regional as joined_regional'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purchase_orders.po_number', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.cust_name', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.phone', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.kode_pra', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.no_order', 'like', "%{$search}%")
                    ->orWhere('orders.status_order', 'like', "%{$search}%")
                    ->orWhere('orders.keterangan', 'like', "%{$search}%");
            });
        }

        $purchaseOrders = $query->orderBy('purchase_orders.created_at', 'desc')->paginate(10)->appends($request->query());

        return view('isp.purchase-orders.index', compact('purchaseOrders'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'coverage_request_id' => 'required|exists:coverage_requests,req_id',
            'po_number' => 'nullable|string|max:255',
            'po_document' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        try {
            return \DB::transaction(function () use ($request, $user, $validated) {
                // Lock the coverage request to prevent concurrent PO creation
                $coverageRequest = CoverageRequest::where('req_id', $validated['coverage_request_id'])
                    ->where('user_id', $user->user_id)
                    ->where('status', 'COVERED')
                    ->lockForUpdate()
                    ->firstOrFail();

                // Prevent duplicate PO for the same coverage request
                $existingPo = PurchaseOrder::where('coverage_request_id', $coverageRequest->req_id)->first();
                if ($existingPo) {
                    return redirect()->route('isp.purchase-orders.index')
                        ->with('error', 'Purchase Order untuk coverage request ini sudah ada.');
                }

                $nextOrderNumber = PurchaseOrder::generateNoOrder();

                // Handle PDF upload
                $pdfPath = null;
                if ($request->hasFile('po_document')) {
                    $file = $request->file('po_document');
                    $disk = Storage::disk('google');
                    $dateFolder = Carbon::now()->format('Y-m-d');

                    // Robust check: explicitly list directories to avoid duplicates due to GDrive indexing latency
                    $dirs = $disk->directories('/');
                    if (! in_array($dateFolder, $dirs)) {
                        $disk->makeDirectory($dateFolder);
                    }

                    // Store to Google Drive: PO ISP (root) / YYYY-MM-DD / filename
                    $pdfPath = $disk->putFileAs($dateFolder, $file, $file->getClientOriginalName());
                    \Illuminate\Support\Facades\Log::info('GDrive Upload Result Path: '.$pdfPath);
                }

                PurchaseOrder::create([
                    'coverage_request_id' => $coverageRequest->req_id,
                    'user_id' => $user->user_id,
                    'po_number' => $validated['po_number'] ?? null,
                    'no_order' => $nextOrderNumber,
                    'brand' => $coverageRequest->brand,
                    'isp_name' => $coverageRequest->isp_name,
                    'area' => $coverageRequest->area,
                    'layanan' => $coverageRequest->layanan,
                    'paket' => $coverageRequest->paket,
                    'kode_pra' => $coverageRequest->kode_pra,
                    'phone' => $coverageRequest->phone,
                    'cust_name' => $coverageRequest->cust_name,
                    'cust_add' => $coverageRequest->cust_add,
                    'longlat' => $coverageRequest->longlat,
                    'bandwidth' => $coverageRequest->bandwidth,
                    'odp' => $coverageRequest->odp,
                    'gpon' => $coverageRequest->gpon,
                    'po_document' => $pdfPath,
                ]);

                return redirect()->route('isp.purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat.');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry if the race condition still hits the unique constraint
            if ($e->getCode() == 23000) {
                return redirect()->route('isp.purchase-orders.index')
                    ->with('error', 'Purchase Order untuk coverage request ini sudah ada (Duplicate).');
            }
            throw $e;
        }
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->user_id !== auth()->user()->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'po_number' => 'nullable|string|max:255',
            'no_order' => 'required|string|max:255',
            'cust_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'cust_add' => 'required|string',
            'longlat' => 'nullable|string|max:255',
            'bandwidth' => 'nullable|string|max:255',
            'odp' => 'nullable|string|max:255',
            'gpon' => 'nullable|string|max:255',
            'reason_cancel' => 'nullable|string|max:255',
            'category_cancel' => 'nullable|string|max:255',
            'po_document' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('po_document')) {
            // Delete old file
            $disk = Storage::disk('google');
            $file = $request->file('po_document');
            $dateFolder = Carbon::now()->format('Y-m-d');

            $dirs = $disk->directories('/');
            if (! in_array($dateFolder, $dirs)) {
                $disk->makeDirectory($dateFolder);
            }

            $validated['po_document'] = $disk->putFileAs($dateFolder, $file, $file->getClientOriginalName());
        }

        $purchaseOrder->update($validated);

        return redirect()->route('isp.purchase-orders.index')->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->user_id !== auth()->user()->user_id) {
            abort(403);
        }

        if ($purchaseOrder->po_document) {
            Storage::disk('google')->delete($purchaseOrder->po_document);
        }

        $purchaseOrder->delete();

        return redirect()->route('isp.purchase-orders.index')->with('success', 'Purchase Order berhasil dihapus.');
    }

    public function viewPdf(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->user_id !== auth()->user()->user_id) {
            abort(403);
        }

        if (! $purchaseOrder->po_document || ! Storage::disk('google')->exists($purchaseOrder->po_document)) {
            abort(404, 'File PDF tidak ditemukan di Google Drive.');
        }

        return response(Storage::disk('google')->get($purchaseOrder->po_document))
            ->header('Content-Type', 'application/pdf');
    }

    public function export()
    {
        return IspPurchaseOrderExport::download(auth()->user()->user_id);
    }
}
