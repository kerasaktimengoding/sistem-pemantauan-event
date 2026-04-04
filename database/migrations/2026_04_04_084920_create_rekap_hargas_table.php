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
            $table->unsignedBigInteger('komoditas_id');
            $table->unsignedBigInteger('wilayah_id');
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
