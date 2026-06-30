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
        Schema::create('rekap_hargas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rekap_harga', 20);
            $table->unsignedBigInteger('input_harga_id')->nullable();
            $table->unsignedBigInteger('komoditas_id');
            $table->unsignedBigInteger('kecamatan_id')->nullable(); // 4. ID Induk Kecamatan
            $table->unsignedBigInteger('desa_id')->nullable();     
            $table->unsignedBigInteger('pasar_id')->nullable();
            $table->unsignedBigInteger('tempat_id')->nullable();    
            $table->unsignedBigInteger('pedagang_id')->nullable();
            $table->date('periode_rekap');
            $table->decimal('harga_rata_rata', 12, 2);
            $table->decimal('harga_maksimum', 12, 2);
            $table->decimal('harga_minimum', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_hargas');
    }
};
