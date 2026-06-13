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
        Schema::create('tempat_usahas', function (Blueprint
         $table) {
            $table->id();
            // Kode Unik Inventaris Tempat Usaha (Misal: TU-001, TOKO-05)
            $table->string('kode_tempat_usaha', 20)->unique();
            
            // Kolom Blok / Nomor Tempat Usaha Fisika (Misal: Blok A No. 12)
            $table->string('nomor_tempat', 50);
            
            // Dimensi / Ukuran Fisik Tempat Usaha (Misal: 3x4 m, 2x2 m)
            $table->string('luas_tempat', 20)->nullable();
            
            // Status Operasional Lapak/Kios/Toko
            $table->enum('status_tempat', ['Buka', 'Tutup', 'Kosong'])->default('Kosong');
            
            // Catatan tambahan mengenai kondisi fisik atau histori tempat
            $table->text('keterangan')->nullable();

            $table->unsignedBigInteger('pasar_id');
            $table->unsignedBigInteger('pedagang_id');
            $table->unsignedBigInteger('wilayah_id');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat_usahas');
    }
};
