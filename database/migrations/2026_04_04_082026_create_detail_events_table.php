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
        Schema::create('detail_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('kode_detail_event', 20);
            $table->text('deskripsi_event');
            $table->decimal('anggaran_event', 14, 2);
            $table->string('penyelenggara', 100);
            $table->string('narasumber', 100);
            $table->integer('kuota_peserta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_events');
    }
};
