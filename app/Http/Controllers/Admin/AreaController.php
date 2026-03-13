<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AreaExport;
use App\Exports\AreaTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\AreaImport;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $query = Area::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sto', 'like', "%{$search}%")
                    ->orWhere('nama_sto', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%");
            });
        }

        $areas = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.area.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sto' => 'required|string|unique:areas,sto',
            'nama_sto' => 'required|string',
            'area' => 'required|string',
            'regional' => 'required|string',
            'branch' => 'required|string',
        ]);

        Area::create($validated);

        return back()->with('success', 'Data Area berhasil ditambahkan.');
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'sto' => 'required|string|unique:areas,sto,'.$area->id,
            'nama_sto' => 'required|string',
            'area' => 'required|string',
            'regional' => 'required|string',
            'branch' => 'required|string',
        ]);

        $area->update($validated);

        return back()->with('success', 'Data Area berhasil diperbarui.');
    }

    public function destroy(Area $area)
    {
        $area->delete();

        return back()->with('success', 'Data Area berhasil dihapus.');
    }

    public function export()
    {
        return AreaExport::download();
    }

    public function template()
    {
        return AreaTemplateExport::download();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);

        try {
            AreaImport::import($request->file('file'));

            return back()->with('success', 'Data Area berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: '.$e->getMessage());
        }
    }
}
