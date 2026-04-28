<?php

namespace App\Http\Controllers;

use App\Models\Meja;

class MejaController extends Controller
{
    public function setMeja($nomor)
    {
        $meja = Meja::where('nomor_meja', $nomor)->first();

        if (!$meja) {
            return redirect('/')
                ->with('error', 'QR Code tidak valid atau tidak terdaftar');
        }

        if (session('meja_id') && session('meja_id') != $meja->id) {
            session()->forget(['meja_id', 'nomor_meja', 'cart']);
        }

        session([
            'meja_id' => $meja->id,
            'nomor_meja' => $meja->nomor_meja,
        ]);

        return redirect('/menu');
    }
}
