<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coverage_requests', function (Blueprint $table) {
            $table->id('req_id');
            $table->unsignedBigInteger('user_id');
            $table->string('brand');
            $table->string('isp_name');
            $table->string('area');
            $table->enum('layanan', ['Vula', 'Bitstream']);
            $table->enum('paket', ['Standar', 'Lite']);
            $table->string('kode_pra')->nullable();
            $table->string('phone');
            $table->string('cust_name');
            $table->text('cust_add');
            $table->string('longlat')->nullable();
            $table->string('bandwidth')->nullable();
            $table->string('odp')->nullable();
            $table->string('gpon')->nullable();
            $table->enum('status', ['PROCESSING', 'COVERED', 'NOT COVERED'])->default('PROCESSING');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coverage_requests');
    }
};
