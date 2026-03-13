<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    public function index()
    {
        // Hardcoded status order as requested
        $statuses = [
            'CANCELED INPUT',
            'WAITING MILESTONE',
            'PROVISION START',
            'FALLOUT',
            'PROVISION ISSUED',
            'OSS TESTING SERVICE',
            'PROVISION COMPLETED',
            'CANCELED',
        ];

        // 1. Total RE Stats
        $reStats = DB::table('purchase_orders')
            ->leftJoin('orders', DB::raw("COALESCE(NULLIF(purchase_orders.admin_no_order_input, ''), purchase_orders.admin_no_order)"), '=', 'orders.no_order')
            ->whereNotNull('orders.status_order')
            ->whereNotIn('orders.status_order', ['CANCELED INPUT', 'CANCELED', '-', ''])
            ->select('purchase_orders.area', DB::raw('count(*) as count'))
            ->groupBy('purchase_orders.area')
            ->pluck('count', 'area');

        // 2. Total PO Stats
        $poStats = PurchaseOrder::whereNotNull('po_number')
            ->select('area', DB::raw('count(*) as count'))
            ->groupBy('area')
            ->pluck('count', 'area');

        // 3. Total PS Stats
        $psStats = DB::table('purchase_orders')
            ->leftJoin('orders', DB::raw("COALESCE(NULLIF(purchase_orders.admin_no_order_input, ''), purchase_orders.admin_no_order)"), '=', 'orders.no_order')
            ->where('orders.status_order', 'PROVISION COMPLETED')
            ->select('purchase_orders.area', DB::raw('count(*) as count'))
            ->groupBy('purchase_orders.area')
            ->pluck('count', 'area');

        // 4. Total Cancel Stats
        $cancelStats = PurchaseOrder::where(function ($q) {
            $q->where('admin_no_order', 'like', '%Cancel PO%')
                ->orWhere('admin_no_order_input', 'like', '%Cancel PO%');
        })
            ->select('area', DB::raw('count(*) as count'))
            ->groupBy('area')
            ->pluck('count', 'area');

        // Base join logic - Primary source is purchase_orders linked to orders
        $baseJoin = PurchaseOrder::leftJoin('orders', DB::raw("COALESCE(NULLIF(purchase_orders.admin_no_order_input, ''), purchase_orders.admin_no_order)"), '=', 'orders.no_order')
            ->leftJoin('areas', 'purchase_orders.sto', '=', 'areas.sto');

        // Table 1: Brand
        $brandData = (clone $baseJoin)
            ->whereNotNull('orders.status_order')
            ->select(
                'purchase_orders.brand',
                'orders.status_order',
                DB::raw('COUNT(purchase_orders.po_id) as total')
            )
            ->groupBy('purchase_orders.brand', 'orders.status_order')
            ->get();

        // Table 2: Branch
        $branchData = (clone $baseJoin)
            ->whereNotNull('orders.status_order')
            ->select(
                'areas.branch',
                'orders.status_order',
                DB::raw('COUNT(purchase_orders.po_id) as total')
            )
            ->groupBy('areas.branch', 'orders.status_order')
            ->get();

        // Table 3: Regional
        $regionalData = (clone $baseJoin)
            ->whereNotNull('orders.status_order')
            ->select(
                'areas.regional',
                'orders.status_order',
                DB::raw('COUNT(purchase_orders.po_id) as total')
            )
            ->groupBy('areas.regional', 'orders.status_order')
            ->get();

        return view('admin.reporting.index', [
            'statuses' => $statuses,
            'brandData' => $this->formatPivot($brandData, 'brand'),
            'branchData' => $this->formatPivot($branchData, 'branch'),
            'regionalData' => $this->formatPivot($regionalData, 'regional'),
            'reStats' => $reStats,
            'poStats' => $poStats,
            'psStats' => $psStats,
            'cancelStats' => $cancelStats,
        ]);
    }

    private function formatPivot($data, $rowKey)
    {
        $formatted = [];
        foreach ($data as $item) {
            $key = $item->$rowKey ?? '-';
            $status = $item->status_order;

            if (! isset($formatted[$key])) {
                $formatted[$key] = [];
            }
            $formatted[$key][$status] = $item->total;
        }

        return $formatted;
    }
}
