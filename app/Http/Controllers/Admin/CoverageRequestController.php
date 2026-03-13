<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CoverageExport;
use App\Exports\CoverageTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\AdminCoverageImport;
use App\Models\CoverageRequest;
use Illuminate\Http\Request;

class CoverageRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = CoverageRequest::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('isp_name', 'like', "%{$search}%")
                    ->orWhere('cust_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('kode_pra', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('admin.coverage.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'isp_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'layanan' => 'required|in:Vula,Bitstream,Metro Ethernet',
            'paket' => 'required|in:Standar,Lite',
            'kode_pra' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'cust_name' => 'required|string|max:255',
            'cust_add' => 'required|string',
            'longlat' => 'required|string|max:255',
            'bandwidth' => 'required|in:20 Mbps,30 Mbps,40 Mbps,50 Mbps,100 Mbps,200 Mbps',
            'odp' => 'nullable|string|max:255',
            'gpon' => 'nullable|string|max:255',
            'status' => 'required|in:PROCESSING,COVERED,NOT COVERED',
        ]);

        $validated['user_id'] = auth()->id();
        CoverageRequest::create($validated);

        return redirect()->route('admin.coverage.index')->with('success', 'Coverage request berhasil ditambahkan.');
    }

    public function update(Request $request, CoverageRequest $coverageRequest)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'isp_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'layanan' => 'required|in:Vula,Bitstream,Metro Ethernet',
            'paket' => 'required|in:Standar,Lite',
            'kode_pra' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'cust_name' => 'required|string|max:255',
            'cust_add' => 'required|string',
            'longlat' => 'required|string|max:255',
            'bandwidth' => 'required|in:20 Mbps,30 Mbps,40 Mbps,50 Mbps,100 Mbps,200 Mbps',
            'odp' => 'nullable|string|max:255',
            'gpon' => 'nullable|string|max:255',
            'status' => 'required|in:PROCESSING,COVERED,NOT COVERED',
        ]);

        $coverageRequest->update($validated);

        return redirect()->route('admin.coverage.index')->with('success', 'Coverage request berhasil diperbarui.');
    }

    public function destroy(CoverageRequest $coverageRequest)
    {
        $coverageRequest->delete();

        return redirect()->route('admin.coverage.index')->with('success', 'Coverage request berhasil dihapus.');
    }

    public function export()
    {
        return CoverageExport::download();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        try {
            AdminCoverageImport::import($request->file('file'));

            return redirect()->back()->with('success', 'Data coverage berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport data: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return CoverageTemplateExport::download();
    }
}
