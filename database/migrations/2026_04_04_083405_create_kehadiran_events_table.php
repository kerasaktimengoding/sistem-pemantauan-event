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
        Schema::create('kehadiran_events', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kehadiran', 20);
            $table->unsignedBigInteger('peserta_event_id');
            $table->string('status_kehadiran', 20);
            $table->dateTime('waktu_kehadiran');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_events');
    }
};
