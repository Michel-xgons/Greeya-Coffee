<?php

namespace App\Http\Controllers;

use App\Models\Meja;

class MejaController extends Controller
{
    public function setMeja($nomor)
    {
        $meja = Meja::where('nomor_meja', $nomor)
            ->where('status', 'kosong')
            ->firstOrFail();

        // simpan ke session
        session([
            'meja_id' => $meja->id,
            'nomor_meja' => $meja->nomor_meja,
        ]);
        

        return redirect('/'); // arahkan ke beranda
    }
}
