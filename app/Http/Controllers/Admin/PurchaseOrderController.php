<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PurchaseOrderExport;
use App\Exports\PurchaseOrderTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\PurchaseOrderImport;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['user', 'areaInfo'])
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
                // Table: purchase_orders
                $q->where('purchase_orders.po_number', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.cust_name', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.phone', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.kode_pra', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.no_order', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.cust_add', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.area', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.layanan', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.paket', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.bandwidth', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.odp', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.gpon', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.sto', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.brand', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.isp_name', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.admin_no_order', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.admin_no_order_input', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.reason_cancel', 'like', "%{$search}%")
                    ->orWhere('purchase_orders.category_cancel', 'like', "%{$search}%")
                    // Joined Table: isps
                    ->orWhere('isps.isp_name', 'like', "%{$search}%")
                    // Joined Table: orders
                    ->orWhere('orders.status_order', 'like', "%{$search}%")
                    ->orWhere('orders.keterangan', 'like', "%{$search}%")
                    // Relations
                    ->orWhereHas('areaInfo', function ($q) use ($search) {
                        $q->where('branch', 'like', "%{$search}%")
                            ->orWhere('regional', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('area')) {
            $query->where('purchase_orders.area', $request->area);
        }

        if ($request->get('filter') === 're') {
            $query->whereNotNull('orders.status_order')
                ->whereNotIn('orders.status_order', ['CANCELED INPUT', 'CANCELED', '-', '']);
        }

        if ($request->get('filter') === 'has_po') {
            $query->whereNotNull('purchase_orders.po_number');
        }

        if ($request->filled('brand')) {
            $query->where('purchase_orders.brand', $request->brand);
        }

        if ($request->filled('status_order')) {
            if ($request->status_order === '-') {
                $query->whereNull('orders.status_order');
            } else {
                $query->where('orders.status_order', $request->status_order);
            }
        }

        if ($request->filled('branch')) {
            $query->where('areas.branch', $request->branch);
        }

        if ($request->filled('regional')) {
            $query->where('areas.regional', $request->regional);
        }

        $purchaseOrders = $query->orderBy('purchase_orders.created_at', 'desc')->paginate(10)->appends($request->query());

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'po_number' => 'nullable|string|max:255',
            'no_order' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'isp_name' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'cust_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'cust_add' => 'required|string',
            'longlat' => 'nullable|string|max:255',
            'bandwidth' => 'nullable|string|max:255',
            'odp' => 'nullable|string|max:255',
            'gpon' => 'nullable|string|max:255',
            'sto' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'regional' => 'nullable|string|max:255',
            'admin_no_order' => 'nullable|string|max:255',
            'admin_no_order_input' => 'nullable|string|max:255',
            'reason_cancel' => 'nullable|string|max:255',
            'category_cancel' => 'nullable|string|max:255',
            'po_document' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('po_document')) {
            $disk = Storage::disk('google');
            if ($purchaseOrder->po_document) {
                $disk->delete($purchaseOrder->po_document);
            }
            $file = $request->file('po_document');
            $dateFolder = Carbon::now()->format('Y-m-d');

            // Robust check: explicitly list directories to avoid duplicates due to GDrive indexing latency
            $dirs = $disk->directories('/');
            if (! in_array($dateFolder, $dirs)) {
                $disk->makeDirectory($dateFolder);
            }

            $validated['po_document'] = $disk->putFileAs($dateFolder, $file, $file->getClientOriginalName());
        }

        $purchaseOrder->update($validated);

        return redirect()->route('admin.purchase-orders.index')->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->po_document) {
            Storage::disk('google')->delete($purchaseOrder->po_document);
        }

        $purchaseOrder->delete();

        return redirect()->route('admin.purchase-orders.index')->with('success', 'Purchase Order berhasil dihapus.');
    }

    public function viewPdf(PurchaseOrder $purchaseOrder)
    {
        if (! $purchaseOrder->po_document || ! Storage::disk('google')->exists($purchaseOrder->po_document)) {
            abort(404, 'File PDF tidak ditemukan di Google Drive.');
        }

        return response(Storage::disk('google')->get($purchaseOrder->po_document))
            ->header('Content-Type', 'application/pdf');
    }

    public function export()
    {
        return PurchaseOrderExport::download();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);

        try {
            $results = PurchaseOrderImport::import($request->file('file'));
            $msg = 'Purchase Orders berhasil diproses. '.
                "Total: {$results['total_rows']} baris. ".
                "Hasil: {$results['created']} baru, {$results['updated']} diperbarui. ".
                "Dilewati: {$results['skipped']} (tidak lengkap). ".
                "Gagal: {$results['failed']} (error).";

            return redirect()->route('admin.purchase-orders.index')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return PurchaseOrderTemplateExport::download();
    }

    public function clearAll()
    {
        if (auth()->user()->role !== 'Super Admin') {
            return redirect()->route('admin.purchase-orders.index')->with('error', 'Unauthorized action.');
        }

        PurchaseOrder::truncate();

        return redirect()->route('admin.purchase-orders.index')->with('success', 'Semua data Purchase Order berhasil dihapus.');
    }
}
