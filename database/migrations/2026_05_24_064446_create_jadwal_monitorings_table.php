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
        Schema::create('jadwal_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jadwal', 20)->unique();
            
            // Relasi menggunakan tipe data INT agar sinkron dengan draf skripsimu
            $table->integer('pasar_id');
            $table->integer('pegawai_id');
            $table->integer('desa_id');
            $table->integer('kecamatan_id');
            
            $table->date('tanggal_rencana');
            $table->string('nomor_surat_tugas', 100)->nullable();
            $table->string('status_monitoring', 20)->default('Pending');
            $table->text('catatan_petugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_monitorings');
    }
};
