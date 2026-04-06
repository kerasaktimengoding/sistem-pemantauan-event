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
        Schema::create('hasil_pelatihans', function (Blueprint $table) {
            $table->id();
             $table->string('kode_hasil_pelatihan', 20);
            $table->unsignedBigInteger('peserta_event_id');
            $table->decimal('nilai_pretest', 5, 2);
            $table->decimal('nilai_posttest', 5, 2);
            $table->decimal('nilai_akhir', 5, 2);
            $table->string('status_kelulusan', 20);
            $table->text('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_pelatihans');
    }
};
