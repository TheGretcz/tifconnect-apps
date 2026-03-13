<?php

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing STO Mapping Exceptions...\n";

$testCases = [
    'ODP-CKN-01' => 'GBC',
    'ODP-MBS-05' => 'CID',
    'ODP-KBB-12' => 'KBY',
    'ODP-GBR-03' => 'GBI',
    'ODP-STW-01' => 'STW', // Normal case
];

foreach ($testCases as $odp => $expectedSto) {
    $po = new PurchaseOrder;
    $po->odp = $odp;

    // Trigger the saving event logic manually since we don't want to actually save to DB
    // Or just save and delete
    DB::beginTransaction();
    $po->cust_name = 'Test';
    $po->phone = '0';
    $po->brand = 'TEST';
    $po->no_order = 'TEST'.rand(100, 999);
    $po->save();

    $actualSto = $po->sto;
    $status = ($actualSto === $expectedSto) ? 'PASS' : "FAIL (Got: $actualSto)";
    echo "ODP: $odp | Expected: $expectedSto | Actual: $actualSto | Result: $status\n";

    DB::rollBack();
}
