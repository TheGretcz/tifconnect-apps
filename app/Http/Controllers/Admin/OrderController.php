<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrderExport;
use App\Exports\OrderTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\OrderImport;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('areaInfo');

        // Status counts for Summary Cards
        $statusCounts = Order::select('status_order', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status_order')
            ->pluck('total', 'status_order')
            ->toArray();

        // Total orders count
        $totalOrders = array_sum($statusCounts);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('layanan', 'like', "%{$search}%")
                    ->orWhere('teritory', 'like', "%{$search}%")
                    ->orWhere('paket', 'like', "%{$search}%")
                    ->orWhere('no_order', 'like', "%{$search}%")
                    ->orWhere('olo', 'like', "%{$search}%")
                    ->orWhere('sto', 'like', "%{$search}%")
                    ->orWhere('nd', 'like', "%{$search}%")
                    ->orWhere('status_order', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_order', $request->status);
        }

        // Branch-wise Status Summary Table Data
        $branchStatusData = \Illuminate\Support\Facades\DB::table('orders as o')
            ->leftJoin('areas as a', 'o.sto', '=', 'a.sto')
            ->select('a.branch', 'o.status_order', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->whereNotNull('o.status_order')
            ->where('o.status_order', '!=', '')
            ->groupBy('a.branch', 'o.status_order')
            ->get();

        $allStatuses = array_keys($statusCounts);
        $summaryTable = [];
        foreach ($branchStatusData as $row) {
            $branch = $row->branch ?? 'Unknown';
            $summaryTable[$branch][$row->status_order] = $row->total;
        }
        ksort($summaryTable);

        $orders = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.orders.index', compact('orders', 'statusCounts', 'totalOrders', 'summaryTable', 'allStatuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'layanan' => 'nullable|string|max:255',
            'paket' => 'nullable|string|max:255',
            'no_order' => 'required|string|max:255|unique:orders,no_order',
            'olo' => 'nullable|string|max:255',
            'sto' => 'nullable|string|max:255',
            'nd' => 'nullable|string|max:255',
            'status_order' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Hardcode teritory 2
        $validated['teritory'] = '2';

        Order::create($validated);

        return back()->with('success', 'Data Order berhasil ditambahkan.');
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'layanan' => 'nullable|string|max:255',
            'paket' => 'nullable|string|max:255',
            'no_order' => 'required|string|max:255|unique:orders,no_order,'.$order->id,
            'olo' => 'nullable|string|max:255',
            'sto' => 'nullable|string|max:255',
            'nd' => 'nullable|string|max:255',
            'status_order' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $order->update($validated);

        return back()->with('success', 'Data Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('success', 'Data Order berhasil dihapus.');
    }

    public function clearAll()
    {
        Order::truncate();

        return back()->with('success', 'Semua data order berhasil dihapus.');
    }

    public function export()
    {
        return OrderExport::download();
    }

    public function template()
    {
        return OrderTemplateExport::download();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:51200',
        ]);

        try {
            $imported = OrderImport::import($request->file('file'));

            return back()->with('success', "Data Order berhasil diimport. {$imported} baris data teritori 2 berhasil dimasukkan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: '.$e->getMessage());
        }
    }
}
