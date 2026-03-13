<?php

namespace App\Http\Controllers\Admin;

use App\Exports\IspExport;
use App\Exports\IspTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\IspImport;
use App\Models\Isp;
use Illuminate\Http\Request;

class IspManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Isp::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('isp_code', 'like', "%{$search}%")
                    ->orWhere('isp_brand', 'like', "%{$search}%")
                    ->orWhere('isp_name', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%");
            });
        }

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        $isps = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('admin.isp.index', compact('isps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'isp_brand' => 'required|string|max:255',
            'isp_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'ba' => 'nullable|string|max:255',
            'ca' => 'nullable|string|max:255',
            'sid' => 'nullable|string|max:255',
            'vlan' => 'nullable|string|max:255',
            'layanan' => 'nullable|string|max:255',
            'created_date' => 'nullable|date',
        ]);

        $validated['isp_code'] = Isp::generateIspCode();

        Isp::create($validated);

        return redirect()->route('admin.isp.index')->with('success', 'ISP berhasil ditambahkan.');
    }

    public function update(Request $request, string $isp_code)
    {
        $isp = Isp::findOrFail($isp_code);

        $validated = $request->validate([
            'isp_brand' => 'required|string|max:255',
            'isp_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'ba' => 'nullable|string|max:255',
            'ca' => 'nullable|string|max:255',
            'sid' => 'nullable|string|max:255',
            'vlan' => 'nullable|string|max:255',
            'layanan' => 'nullable|string|max:255',
            'created_date' => 'nullable|date',
        ]);

        $isp->update($validated);

        return redirect()->route('admin.isp.index')->with('success', 'ISP berhasil diperbarui.');
    }

    public function destroy(string $isp_code)
    {
        $isp = Isp::findOrFail($isp_code);
        $isp->delete();

        return redirect()->route('admin.isp.index')->with('success', 'ISP berhasil dihapus.');
    }

    public function export()
    {
        return IspExport::download();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);

        try {
            IspImport::import($request->file('file'));

            return redirect()->route('admin.isp.index')->with('success', 'Data ISP berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('admin.isp.index')->with('error', 'Gagal mengimpor data: '.$e->getMessage());
        }
    }

    public function clearAll()
    {
        if (auth()->user()->role !== 'Super Admin') {
            return redirect()->route('admin.isp.index')->with('error', 'Unauthorized action.');
        }

        Isp::truncate();

        return redirect()->route('admin.isp.index')->with('success', 'Semua data ISP berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        return IspTemplateExport::download();
    }
}
