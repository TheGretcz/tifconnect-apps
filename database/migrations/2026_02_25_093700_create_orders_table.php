<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('layanan')->nullable();
            $table->string('teritory')->nullable();
            $table->string('paket')->nullable();
            $table->string('no_order')->nullable();
            $table->string('olo')->nullable();
            $table->string('sto')->nullable();
            $table->string('nd')->nullable();
            $table->string('status_order')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
