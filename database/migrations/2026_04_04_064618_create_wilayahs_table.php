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
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id();
           // --- IDENTITAS ---
            $table->string('kode_wilayah', 20)->unique();    // 2. Kode BPS / Kemendagri
            $table->string('nama_wilayah', 50);    // 2. Kode BPS / Kemendagri
            // --- HIRARKI ---
            $table->unsignedBigInteger('kecamatan_id')->nullable(); // 4. ID Induk Kecamatan
            $table->unsignedBigInteger('desa_id')->nullable();      // 5. ID Anak Desa
                 // 5. ID Anak Desa

            // --- PROFIL FISIK ---
            $table->decimal('luas_wilayah', 10, 2)->nullable(); // 6. Luas dalam KM persegi
            $table->string('batas_utara')->nullable();          // 7. Batas wilayah Utara
            $table->string('batas_selatan')->nullable();        // 8. Batas wilayah Selatan
            
            // --- DATA SOSIAL EKONOMI ---
            $table->integer('jumlah_penduduk')->default(0);     // 9. Total populasi diambil dari desa 
            $table->string('potensi_ekonomi')->nullable();      // 10. Contoh: Pertanian, Perikanan, Jasa
            $table->text('keterangan_geografis')->nullable();   // 11. Contoh: Dataran rendah, bantaran sungai
            
            $table->char('kode_pos', 5);                        // 12. Kode Pos
            $table->timestamps(); // 13 & 14. Created & Updated at
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayahs');
    }
};
