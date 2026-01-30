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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan');
            $table->string('kode_pembayaran')->unique();
            $table->string('metode_pembayaran');
            $table->integer('total_bayar');
            $table->enum('status_pembayaran', ['pending', 'dibayar', 'gagal', 'kedaluwarsa'])->default('pending');
            $table->timestamp('waktu_bayar')->nullable();
            $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
