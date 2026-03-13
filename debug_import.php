<?php

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Finding duplicates based on Name, Phone, Brand, PO, PRA...\n";

$dupes = DB::table('purchase_orders')
    ->select('cust_name', 'phone', 'brand', 'po_number', 'kode_pra', DB::raw('count(*) as count'))
    ->groupBy('cust_name', 'phone', 'brand', 'po_number', 'kode_pra')
    ->having('count', '>', 1)
    ->get();

echo 'Total duplicate groups: '.$dupes->count()."\n";

foreach ($dupes as $d) {
    echo '- Name: '.($d->cust_name ?: '[EMPTY]').
        ' | Phone: '.($d->phone ?: '[EMPTY]').
        ' | Brand: '.($d->brand ?: '[EMPTY]').
        ' | PO: '.($d->po_number ?: '[EMPTY]').
        ' | PRA: '.($d->kode_pra ?: '[EMPTY]').
        ' | Count: '.$d->count."\n";
}

echo "\nChecking for empty or null in key fields...\n";
echo 'Empty Name: '.PurchaseOrder::where('cust_name', '')->orWhereNull('cust_name')->count()."\n";
echo 'Empty Phone: '.PurchaseOrder::where('phone', '')->orWhereNull('phone')->count()."\n";
echo 'Empty Brand: '.PurchaseOrder::where('brand', '')->orWhereNull('brand')->count()."\n";

echo "\nTotal Purchase Orders: ".PurchaseOrder::count()."\n";
