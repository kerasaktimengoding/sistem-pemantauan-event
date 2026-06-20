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
        Schema::create('tempats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tempat_usaha', 20)->unique();

            // Kolom Blok / Nomor Tempat Usaha Fisika (Misal: Blok A No. 12)
            $table->string('nomor_tempat', 50);

            // Dimensi / Ukuran Fisik Tempat Usaha (Misal: 3x4 m, 2x2 m)
            $table->string('luas_tempat', 20)->nullable();

            // Status Operasional Lapak/Kios/Toko
            $table->enum('status_tempat', ['Buka', 'Tutup', 'Kosong', 'Dijual', 'Disewakan'])->default('Kosong');
            $table->enum('jenis_tempat', [
                'Toko',
                'Kios',
                'Los',
                'Kaki Lima',
                'Lapak',
                'Grosir / Agen',
                'Swalayan',
                'Tenda'
            ])->default('Kios'); // Default diset ke Kios jika tidak diisi
            // Catatan tambahan mengenai kondisi fisik atau histori tempat
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('pasar_id');
            $table->unsignedBigInteger('pedagang_id');
            $table->unsignedBigInteger('kecamatan_id')->nullable(); // 4. ID Induk Kecamatan
            $table->unsignedBigInteger('desa_id')->nullable();      // 5. ID Anak Desa
            $table->string('nomor_hp', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempats');
    }
};
