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
        Schema::create('perbandingan_wilayahs', function (Blueprint $table) {
            $table->id();
              $table->string('kode_perbandingan', 20);
            $table->unsignedBigInteger('komoditas_id');
            $table->unsignedBigInteger('desa_id');
            $table->unsignedBigInteger('desa_2_id');
            $table->decimal('harga_desa_1', 12, 2);
            $table->decimal('harga_desa_2', 12, 2);
            $table->decimal('selisih_harga', 12, 2);
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbandingan_wilayahs');
    }
};
