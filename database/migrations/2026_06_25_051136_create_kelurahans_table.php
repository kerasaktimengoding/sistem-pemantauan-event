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
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelurahan', 20)->unique();              // 1. Kode wilayah kelurahan
            $table->string('nama_kelurahan', 100);                       // 2. Nama kelurahan

            // Relasi ke tabel kecamatans
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');

            $table->string('nama_lurah')->nullable();                    // 4. Nama Lurah
            $table->string('alamat_kantor_kelurahan');                   // 5. Lokasi kantor kelurahan
            $table->char('kode_pos', 5);
            $table->decimal('luas_wilayah', 10, 2)->nullable();         // 6. Luas wilayah (km2)

            $table->string('no_hp_lurah', 15)->nullable();               // 9. No HP Lurah untuk koordinasi cepat
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahans');
    }
};
