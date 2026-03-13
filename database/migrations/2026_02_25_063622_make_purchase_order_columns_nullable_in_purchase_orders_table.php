<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('area')->nullable()->change();
            $table->string('layanan')->nullable()->change();
            $table->string('paket')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('area')->nullable(false)->change();
            $table->string('layanan')->nullable(false)->change();
            $table->string('paket')->nullable(false)->change();
        });
    }
};
