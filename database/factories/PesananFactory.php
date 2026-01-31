<?php

namespace Database\Factories;

use App\Models\Pesanan;
use App\Models\Meja;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesananFactory extends Factory
{
    protected $model = Pesanan::class;

    public function definition(): array
    {
        return [
            'id_meja' => Meja::query()->inRandomOrder()->value('id_meja'),

            'kode_pesanan' => 'ORD-' . $this->faker->unique()->numberBetween(10000, 99999),
            'total_harga' => $this->faker->numberBetween(20000, 150000),
            'status_pesanan' => $this->faker->randomElement(['diproses', 'selesai']),
            'xendit_invoice_id' => null,
        ];
    }
}
