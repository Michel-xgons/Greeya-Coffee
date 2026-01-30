<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_meja')
                ->constrained('meja', 'id_meja')
                ->onDelete('cascade');
            $table->string('kode_pesanan')->unique();
            $table->integer('total_harga');
            $table->enum('status_pesanan', [
                'menunggu',
                'diproses',
                'selesai',
                'dibatalkan'
    ])->default('menunggu');
    $table->string('xendit_invoice_id')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
