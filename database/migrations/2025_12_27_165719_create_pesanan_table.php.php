<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('kode_pesanan');
            $table->dateTime('waktu_pesan');
            $table->string('payment_status')->default('unpaid');
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
