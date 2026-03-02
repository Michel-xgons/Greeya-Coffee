<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PemesananController extends Controller
{
    /**
     * Tampilkan halaman data pemesan
     */
    public function index()
    {
        return view('frontend.Menu.Pemesanan');
    }

    
    // public function simpan(Request $request)
    // {
    //     $validated = $request->validate([
    //         'nama' => "required|string|max:255",
    //         'email' => "required|email",
    //         'telepon' => 'required|string|max:20',
    //     ]);

    //     // Ambil meja dari session (hasil scan QR)
    //     $mejaId = session('meja_id');

    //     // Cegah kalau belum scan QR
    //     if (!$mejaId) {
    //         return redirect('/')
    //             ->with('error', 'Silakan scan QR meja terlebih dahulu.');
    //     }

    //     // Simpan data customer ke session
    //     session()->put('customer', $validated);

    //     // Simpan juga meja_id ke session (kalau mau eksplisit)
    //     session()->put('meja_id', $mejaId);

    //     return redirect()->route('checkout.process');
    // }
}
