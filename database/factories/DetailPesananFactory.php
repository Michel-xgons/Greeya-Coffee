<?php

namespace Database\Factories;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailPesananFactory extends Factory
{
    protected $model = DetailPesanan::class;

    public function definition()
    {
        $jumlah = $this->faker->numberBetween(1, 5);
        $harga  = $this->faker->numberBetween(10000, 50000);

        return [
            'id_pesanan' => Pesanan::factory(),
            'id_menu'    => Menu::factory(),
            'jumlah'     => $jumlah,
            'harga'      => $harga,
            'subtotal'   => $jumlah * $harga,
        ];
    }
}
    