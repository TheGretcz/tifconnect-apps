<?php

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$app->make(Illuminate\Contracts\Http\Kernel::class)->handle(Illuminate\Http\Request::capture());

use App\Models\Order;
use App\Models\PurchaseOrder;

echo "--- DEEP JOIN ANALYSIS ---\n";

$pos = PurchaseOrder::whereNotNull('admin_no_order')
    ->where('admin_no_order', '!=', '')
    ->limit(50)
    ->get();

$matches = 0;
foreach ($pos as $po) {
    $search = trim($po->admin_no_order_input ?: $po->admin_no_order);
    if (empty($search)) {
        continue;
    }

    $ord = Order::where('no_order', $search)->first();

    if ($ord) {
        $matches++;
        echo "[MATCH FOUND] PO ID: {$po->po_id} | PO Search Key: '{$search}' | Order Status: {$ord->status_order}\n";
    } else {
        // Try partial match or other columns
        $partial = Order::where('no_order', 'like', "%{$search}%")->first();
        if ($partial) {
            echo "[PARTIAL MATCH] PO Search Key: '{$search}' -> Found Order: {$partial->no_order} | Status: {$partial->status_order}\n";
        }
    }
}

echo "\nSummary: Checked 50 POs, found $matches exact matches.\n";

echo "\nSample Orders (First 5):\n";
foreach (Order::limit(5)->get() as $o) {
    echo "- No Order: '{$o->no_order}' | Status: '{$o->status_order}'\n";
}
