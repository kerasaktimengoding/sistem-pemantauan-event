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
        Schema::create('komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_komoditas', 20);
            $table->string('nama_komoditas', 100);
            $table->enum('kategori', [
                'beras',
                'bahan_pokok',
                'sayur',
                'buah',
                'bumbu_dapur',
                'protein_hewani',
                'protein_nabati',
                'minyak_lemak',
                'gula',
                'olahan_pangan',
                'sembako_lain',
                'non_pangan',
                'bahan_kue',
                'susu' // Penambahan
            ])->default('sembako_lain');
            // kategori dijadikan satu dan juga satuan 
            $table->enum('satuan', [
                'Kg',
                'Gram',
                'Liter',
                'Ml',
                'Pcs',
                'Ikat',
                'Biji',
                'Karung',
                'Pack',
                'Botol',
                'Box',
                'Tray'
            ])->default('Kg');

            $table->text('deskripsi');
            $table->string('status_komoditas', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komoditas');
    }
};
