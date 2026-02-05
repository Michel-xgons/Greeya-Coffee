<?php

namespace Database\Factories;

use App\Models\Meja;
use Illuminate\Database\Eloquent\Factories\Factory;

class MejaFactory extends Factory
{
    protected $model = Meja::class;

    public function definition(): array
    {
        return [
            'nomor_meja' => $this->faker->unique()->numberBetween(1, 20),
            'qr_code' => $this->faker->uuid(),
            'status' => 'kosong',
        ];
    }
}
