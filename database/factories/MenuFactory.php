<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\KategoriMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'id_kategori' => KategoriMenu::inRandomOrder()->first()->id_kategori,
            'nama_menu'   => $this->faker->words(2, true),
            'harga'       => $this->faker->numberBetween(10000, 50000),
            'deskripsi'   => $this->faker->sentence(),
            'gambar_menu' => 'menu-default.jpg',
            'status'      => $this->faker->randomElement(['tersedia', 'habis']),
        ];
    }
}


