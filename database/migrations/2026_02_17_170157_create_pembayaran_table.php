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
            $table->string('xendit_external_id', 100)->unique();
            $table->string('payment_type', 20)->default('qris');
            $table->string('transaction_status', 50)->nullable();
            $table->decimal('gross_amount', 15, 2);
            $table->text('invoice_url')->nullable();
            $table->dateTime('expiry_time')->nullable();
            $table->timestamp('transaction_time')->nullable();
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
