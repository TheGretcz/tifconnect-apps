<?php

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$app->make(Illuminate\Contracts\Http\Kernel::class)->handle(Illuminate\Http\Request::capture()); // Bootstrap fully

use App\Models\Order;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

echo "--- PO to Order Join Analysis ---\n";

$pos = PurchaseOrder::whereNotNull('admin_no_order')->limit(20)->get();
if ($pos->isEmpty()) {
    echo "No POs found with admin_no_order.\n";
} else {
    foreach ($pos as $po) {
        $search = trim($po->admin_no_order_input ?: $po->admin_no_order);
        $ord = Order::where('no_order', $search)->first();
        echo "PO ADM: '{$po->admin_no_order}' | INP: '{$po->admin_no_order_input}' | Search: '{$search}' | Status: ".($ord ? $ord->status_order : 'NOT FOUND')."\n";
    }
}

$total = DB::table('purchase_orders')->join('orders', 'purchase_orders.admin_no_order', '=', 'orders.no_order')->count();
echo "\nTotal Direct Matches (admin_no_order): $total\n";

$totalInp = DB::table('purchase_orders')->join('orders', 'purchase_orders.admin_no_order_input', '=', 'orders.no_order')->count();
echo "Total Direct Matches (admin_no_order_input): $totalInp\n";

// Check if no_order in orders matches po_number
$matchPO = DB::table('purchase_orders')->join('orders', 'purchase_orders.po_number', '=', 'orders.no_order')->count();
echo "Total Matches (po_number = orders.no_order): $matchPO\n";

// Check sample of orders.no_order
echo "\nSample Order Numbers:\n";
print_r(Order::limit(5)->pluck('no_order')->toArray());
