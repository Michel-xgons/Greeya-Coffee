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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pesanan_id')
                ->constrained('pesanans')
                ->cascadeOnDelete();

            $table->string('external_id')->unique(); // kirim ke Xendit
            $table->string('kode_pembayaran')->unique();

            $table->string('xendit_invoice_id')->nullable();
            $table->string('invoice_url')->nullable();

            $table->string('metode_pembayaran')->default('QRIS');

            $table->bigInteger('total_bayar');

            $table->enum('status_pembayaran', [
                'pending',
                'paid',
                'expired',
                'failed'
            ])->default('pending');

            $table->index('status_pembayaran');

            $table->timestamp('waktu_bayar')->nullable();

            $table->json('callback_payload')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
