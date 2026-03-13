<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $pos = DB::table('purchase_orders')->get();
        foreach ($pos as $po) {
            $coverage = DB::table('coverage_requests')
                ->where('req_id', $po->coverage_request_id)
                ->first();

            if ($coverage && $coverage->longlat) {
                DB::table('purchase_orders')
                    ->where('po_id', $po->po_id)
                    ->update(['longlat' => $coverage->longlat]);
            }
        }
    }

    public function down(): void
    {
        // No easy way to undo backfill without potentially losing data
    }
};
