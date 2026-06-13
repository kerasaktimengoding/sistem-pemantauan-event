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
            Schema::create('tren_hargas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('komoditas_id');
                $table->unsignedBigInteger('kecamatan_id')->nullable(); // 4. ID Induk Kecamatan
                $table->unsignedBigInteger('desa_id')->nullable();      // 5. ID Anak Desa
                $table->date('periode_tren');
                $table->decimal('harga_awal', 12, 2);
                $table->decimal('harga_akhir', 12, 2);
                $table->string('arah_tren', 20);
                $table->decimal('persentase_perubahan', 6, 2);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tren_hargas');
    }
};
