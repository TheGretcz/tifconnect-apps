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
            $table->string('longlat')->after('cust_add')->nullable();
            $table->string('admin_no_order')->after('no_order')->nullable();
            $table->string('admin_no_order_input')->after('admin_no_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['longlat', 'admin_no_order', 'admin_no_order_input']);
        });
    }
};
