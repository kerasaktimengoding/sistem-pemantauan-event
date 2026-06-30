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
        Schema::create('event_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_event', 20);
            $table->string('nama_event', 100);
            $table->string('jenis_event', 50);
            $table->integer('wilayah_id');
            
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('lokasi_event', 150);
            $table->string('status_event', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_kegiatans');
    }
};
