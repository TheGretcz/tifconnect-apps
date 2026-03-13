<?php

use App\Models\PurchaseOrder;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting STO backfill (aggressive)...\n";

$purchaseOrders = PurchaseOrder::whereNotNull('odp')->get();
$updated = 0;

foreach ($purchaseOrders as $po) {
    $parts = explode('-', $po->odp);
    if (count($parts) >= 2) {
        $sto = strtoupper(trim($parts[1]));
        if ($po->sto !== $sto) {
            $po->sto = $sto;
            $po->save();
            $updated++;
            echo "Updated PO ID: {$po->po_id} with STO: {$sto}\n";
        }
    }
}

echo "Finished. Total records updated: {$updated}\n";
