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
        Schema::create('input_hargas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_input_harga', 20);
            $table->unsignedBigInteger('komoditas_id');
            $table->unsignedBigInteger('wilayah_id');
            $table->unsignedBigInteger('pasar_id');
            $table->unsignedBigInteger('pedagang_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->decimal('harga', 12, 2);
            $table->date('tanggal_input');
            $table->string('sumber_data', 50);
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_hargas');
    }
};
