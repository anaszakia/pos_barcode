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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('kode_reservasi')->unique(); // RSV + ymd + urutan
            $table->string('nama');
            $table->string('no_hp');
            $table->integer('jumlah_orang');
            $table->date('tanggal');
            $table->time('jam');
            $table->enum('status_pembayaran', ['pending', 'sukses'])->default('pending');
            $table->enum('metode_dp', ['transfer', 'kasir']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
