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

        // $total = 0;
        // foreach ($cart as $item) {
        //     $total += $item['harga'] * $item['qty'];
        // }

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
        $varian = trim($request->varian ?? '');
        $note   = trim($request->note ?? '');

        $row_id = md5($menu->id . '-' . $varian);

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

        session()->put('cart', $cart);

        $total = 0;
        $html = '';

        foreach ($cart as $item) {

            $subtotal = $item['harga'] * $item['qty'];
            $total += $subtotal;

            $html = view('frontend.partials.cart_items', [
                'cart' => $cart
            ])->render();
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
        $qty = $request->query('qty', 1);

        return view('frontend.Menu.DetailMenu', compact('menu', 'from', 'qty'));
    }
}
