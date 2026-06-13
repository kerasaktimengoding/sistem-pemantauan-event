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
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();

            // --- 10 KOLOM UTAMA ---
            $table->string('kode_kecamatan', 20)->unique();    // 1. Kode wilayah
            $table->string('nama_kecamatan', 100);              // 2. Nama wilayah
            $table->string('nama_camat')->nullable();           // 3. Nama pimpinan
            $table->string('nip_camat', 20)->nullable();        // 4. NIP pimpinan
            $table->string('alamat_kantor');                    // 5. Lokasi kantor kecamatan
            $table->string('no_telp', 15)->nullable();          // 6. Nomor telepon kantor
            $table->string('email_kecamatan')->nullable();      // 7. Email resmi
            $table->decimal('luas_wilayah', 10, 2)->nullable(); // 8. Luas wilayah (km2)
            $table->integer('jumlah_penduduk')->default(0);     // 9. Estimasi penduduk
            $table->string('keterangan')->nullable();           // 10. Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
    }
};
