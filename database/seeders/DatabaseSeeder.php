<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\DetailPesanan;
use App\Models\KategoriMenu;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{

    KategoriMenu::create(['nama_kategori' => 'Makanan']);
    KategoriMenu::create(['nama_kategori' => 'Minuman']);
    Meja::factory(10)->create();

    // DATA TRANSAKSI
    Menu::factory(20)->create();
    Pesanan::factory(15)->create();
    DetailPesanan::factory(20)->create();
    Payment::factory(15)->create();
    Customer::factory(10)->create();
}

}
