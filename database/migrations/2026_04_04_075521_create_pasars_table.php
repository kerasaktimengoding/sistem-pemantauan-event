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
            Schema::create('pasars', function (Blueprint $table) {
                $table->id();
                $table->string('kode_pasar', 20);
                $table->string('nama_pasar', 100);
                $table->unsignedBigInteger('kecamatan_id')->nullable(); // 4. ID Induk Kecamatan
                $table->unsignedBigInteger('desa_id')->nullable();      // 5. ID Anak Desa
                $table->text('alamat_pasar');
                $table->string('status_pasar', 20);
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasars');
    }
};
