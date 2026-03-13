<?php

namespace App\Http\Controllers\Isp;

use App\Exports\CoverageRequestTemplateExport;
use App\Exports\IspCoverageExport;
use App\Http\Controllers\Controller;
use App\Imports\CoverageRequestImport;
use App\Models\CoverageRequest;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = CoverageRequest::where('user_id', $user->user_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('cust_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('cust_add', 'like', "%{$search}%")
                    ->orWhere('kode_pra', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%")
                    ->orWhere('layanan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->with('purchaseOrder')->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        $totalRequests = CoverageRequest::where('user_id', $user->user_id)->count();
        $totalCovered = CoverageRequest::where('user_id', $user->user_id)->where('status', 'COVERED')->count();
        $totalProcessing = CoverageRequest::where('user_id', $user->user_id)->where('status', 'PROCESSING')->count();
        $totalNotCovered = CoverageRequest::where('user_id', $user->user_id)->where('status', 'NOT COVERED')->count();
        $totalPO = PurchaseOrder::where('user_id', $user->user_id)->whereNotNull('po_number')->count();

        return view('isp.dashboard', compact('requests', 'totalRequests', 'totalCovered', 'totalProcessing', 'totalNotCovered', 'totalPO'));
    }

    public function export()
    {
        return IspCoverageExport::download(auth()->user()->user_id);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);

        try {
            CoverageRequestImport::import($request->file('file'));

            return redirect()->route('isp.dashboard')->with('success', 'Coverage Requests berhasil di-import.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return CoverageRequestTemplateExport::download();
    }
}
