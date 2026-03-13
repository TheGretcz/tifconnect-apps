<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $pos = \App\Models\PurchaseOrder::all();
        foreach ($pos as $po) {
            if ($po->odp && empty($po->sto)) {
                $parts = explode('-', $po->odp);
                if (count($parts) >= 2) {
                    $po->sto = strtoupper($parts[1]);
                    $po->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
