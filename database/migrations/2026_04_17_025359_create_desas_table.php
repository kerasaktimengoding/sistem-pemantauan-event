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
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            // --- 10 KOLOM UTAMA ---
            $table->string('kode_desa', 20)->unique();          // 1. Kode wilayah
            $table->string('nama_desa', 100);                    // 2. Nama desa
            $table->unsignedBigInteger('kecamatan_id');
            $table->string('nama_pembakal')->nullable();        // 4. Nama Kepala Desa
            $table->string('alamat_kantor_desa');               // 5. Lokasi kantor desa
            $table->char('kode_pos', 5);     
             $table->decimal('luas_wilayah', 10, 2)->nullable();                   // 6. Kode pos
           
            $table->string('no_hp_pembakal', 15)->nullable();   // 9. No HP untuk koordinat cepat
            $table->boolean('is_active')->default(true);        // 10. Status keaktifan data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desas');
    }
};
