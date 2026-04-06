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
        Schema::create('peserta_events', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peserta_event', 20);
            $table->unsignedBigInteger('event_id');
            $table->string('nik', 20);
            $table->string('nama_peserta', 100);
            $table->string('nama_usaha', 100);
            $table->string('jenis_produk', 100);
            $table->string('jenis_kelamin', 10);
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->unsignedBigInteger('wilayah_id');
            $table->date('tanggal_registrasi');
            $table->string('status_partisipasi', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_events');
    }
};
