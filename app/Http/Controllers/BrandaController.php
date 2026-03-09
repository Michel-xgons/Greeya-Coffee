<?php

namespace App\Http\Controllers;


use App\Models\Kategoris;
use App\Models\Menus;
use Illuminate\Http\Request;

class BrandaController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        $kategoris = Kategoris::with('menus')->get();
        // dd($kategoris);

        return view(
            'frontend.Menu.Branda',
            [
                'kategoris'      => $kategoris
            ]
        );
    }

    public function cart_add(Request $request)
    {
        $menu = Menus::findOrFail($request->id);

        $cart = session('cart', []);

        $qty     = (int) $request->qty;
        $varian = $request->varian;
        $note    = $request->note ?? '';

        $row_id = md5($menu->id . '-' . $varian . '-' . $note);

        if (isset($cart[$row_id])) {

            $cart[$row_id]['qty'] += $qty;
        } else {

            $cart[$row_id] = [
                'row_id' => $row_id,
                'id'      => $menu->id,
                'nama'    => $menu->nama_menu,
                'harga'   => $menu->harga,
                'gambar'  => $menu->gambar,
                'qty'     => $qty,
                'varian' => $varian,
                'note'    => $note,
            ];
        }

        // session(['cart' => $cart]);
        session()->put('cart', $cart);

        $total = 0;
        $html = '';

        foreach ($cart as $item) {

            $subtotal = $item['harga'] * $item['qty'];
            $total += $subtotal;

            $html .= '
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">

            <div class="text-start">
                <strong>' . $item['nama'] . '</strong><br>
                <small class="text-muted">
                ' . $item['qty'] . ' x Rp ' . number_format($item['harga'], 0, ',', '.') . '
                </small>
            </div>

            <div class="fw-bold">
                Rp ' . number_format($subtotal, 0, ',', '.') . '
            </div>

        </div>';
        }

        return response()->json([
            'success' => true,
            'html' => $html,
            'total' => $total,
            'total_item' => collect($cart)->sum('qty')
        ]);
    }

    public function show($id_menu, Request $request)
    {
        $menu = Menus::findOrFail($id_menu);

        $from = $request->query('from');

        return view('frontend.Menu.DetailMenu', compact('menu', 'from'));
    }
}
