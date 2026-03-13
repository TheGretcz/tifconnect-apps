<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoverageRequest;
use App\Models\Isp;
use App\Models\PurchaseOrder;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIsp = Isp::count();
        $totalCovered = CoverageRequest::where('status', 'COVERED')->count();
        $totalProcessing = CoverageRequest::where('status', 'PROCESSING')->count();
        $totalNotCovered = CoverageRequest::where('status', 'NOT COVERED')->count();
        $totalUsers = User::count();
        $totalPO = PurchaseOrder::whereNotNull('po_number')->count();
        $recentRequests = CoverageRequest::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalIsp',
            'totalCovered',
            'totalProcessing',
            'totalNotCovered',
            'totalUsers',
            'totalPO',
            'recentRequests'
        ));
    }
}
