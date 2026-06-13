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
        Schema::create('pedagangs', function (Blueprint $table) {
            $table->id();
             $table->string('nik', 20);
            $table->string('kode_pedagang', 20);
            $table->string('nama_pedagang', 100);
            $table->string('jenis_tempat', 50);
            $table->string('no_hp', 15);
            $table->unsignedBigInteger('kecamatan_id')->nullable(); // 4. ID Induk Kecamatan
            $table->unsignedBigInteger('desa_id')->nullable();      // 5. ID Anak Desa
            $table->text('alamat');
            // $table->unsignedBigInteger('wilayah_id');
            $table->string('status_pedagang', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedagangs');
    }
};
