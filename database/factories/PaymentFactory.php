<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'id_pesanan' => Pesanan::inRandomOrder()->first()->id_pesanan,

            'kode_pembayaran' => 'PAY-' . $this->faker->unique()->numberBetween(10000, 99999),

            'metode_pembayaran' => $this->faker->randomElement([
                'cash', 'qris', 'transfer'
            ]),

            'total_bayar' => $this->faker->numberBetween(20000, 150000),

            'status_pembayaran' => $this->faker->randomElement([
                'pending', 'dibayar', 'gagal'
            ]),

            'waktu_bayar' => null,
        ];
    }
}
