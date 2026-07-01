<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perbandingan_wilayahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_perbandingan', 20);
            $table->unsignedBigInteger('input_harga_id')->nullable();
            $table->date('periode_rekap');
            $table->unsignedBigInteger('komoditas_id');
            $table->unsignedBigInteger('pasar_id');
            $table->unsignedBigInteger('pasar_2_id');
            $table->decimal('harga_pasar_1', 12, 2);
            $table->decimal('harga_pasar_2', 12, 2);

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
