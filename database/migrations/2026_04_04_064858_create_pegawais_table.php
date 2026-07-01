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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 30);
            $table->string('nik', 20);
            $table->string('nama_pegawai', 100);
            $table->string('jenis_kelamin', 10);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->text('is_luar_kabupaten')->nullable();
            $table->string('alamat_luar')->nullable();
            $table->string('no_hp', 15);
            $table->string('email', 100);
            $table->unsignedBigInteger('jabatan_id');
            $table->unsignedBigInteger('kecamatan_id'); // wilayah kerja kecamatan dan desa didalam kabupaten banjar dan diluar 
            $table->unsignedBigInteger('desa_id'); // wilayah kerja kecamatan dan desa didalam kabupaten banjar dan diluar 
            $table->string('status_pegawai', 20);
            $table->date('tanggal_masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
