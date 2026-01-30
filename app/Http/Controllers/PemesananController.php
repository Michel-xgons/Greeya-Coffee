<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class PemesananController extends Controller
{
    /**
     * Tampilkan halaman data pemesan
     */
    public function index()
    {
        return view('frontend.Menu.Pemesanan');
    }

    /**
     * Simpan data pemesan & pesanan
     */
    public function simpan(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
            'items' => 'required|array',
            'total' => 'required|numeric',
        ]);

        // 2. Simpan / ambil customer
        $customer = Customer::firstOrCreate(
            ['email' => $request->email],
            [
                'nama' => $request->nama,
                'telepon' => $request->telepon,
            ]
        );

        // 3. Simpan pesanan
        $pesanan = Pesanan::create([
            'id_pesanan' => Str::uuid(),
            'kode_pesanan' => 'GRY-' . time(),
            'total_harga' => $request->total,
            'status_pesanan' => 'pending',
        ]);

        // 4. Simpan detail pesanan
        foreach ($request->items as $item) {
            DetailPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'id_menu' => $item['id_menu'] ?? null,
                'jumlah' => $item['qty'],
                'harga' => $item['price'],
                'subtotal' => $item['qty'] * $item['price'],
            ]);
        }

        // 5. Response ke frontend
        return response()->json([
            'success' => true,
            'id_pesanan' => $pesanan->id_pesanan,
        ]);
    }
}
