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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meja_id');
            $table->string('kode_pesanan')->unique(); // contoh: ORD20240623001
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->timestamps();

            $table->foreign('meja_id')->references('id')->on('mejas')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
