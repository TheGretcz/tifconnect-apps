<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('isps', function (Blueprint $table) {
            $table->string('isp_code')->primary();
            $table->string('isp_brand');
            $table->string('isp_name');
            $table->string('area');
            $table->string('ba')->nullable();
            $table->string('ca')->nullable();
            $table->string('sid')->nullable();
            $table->string('vlan')->nullable();
            $table->string('layanan')->nullable();
            $table->date('created_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('isps');
    }
};
