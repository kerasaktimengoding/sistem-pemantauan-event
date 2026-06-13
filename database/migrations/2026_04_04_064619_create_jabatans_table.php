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
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jabatan', 20);
            $table->string('nama_jabatan', 100);
            $table->text('tugas_pokok');
            $table->text('wewenang'); // Wewenang yang dimiliki oleh pejabat
            $table->enum('status_jabatan', ['aktif', 'non-aktif'])->default('aktif'); // Status jabatan
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
