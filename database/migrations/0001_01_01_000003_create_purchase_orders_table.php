<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('po_id');
            $table->unsignedBigInteger('coverage_request_id');
            $table->unsignedBigInteger('user_id');
            $table->string('po_number')->nullable();
            $table->string('no_order')->nullable();
            $table->string('brand');
            $table->string('isp_name');
            $table->string('area');
            $table->string('layanan');
            $table->string('paket');
            $table->string('kode_pra')->nullable();
            $table->string('phone');
            $table->string('cust_name');
            $table->text('cust_add');
            $table->string('bandwidth')->nullable();
            $table->string('odp')->nullable();
            $table->string('gpon')->nullable();
            $table->string('po_document')->nullable();
            $table->timestamps();

            $table->foreign('coverage_request_id')->references('req_id')->on('coverage_requests')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
