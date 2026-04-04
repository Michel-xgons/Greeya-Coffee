<?php

namespace App\Http\Controllers;


use App\Models\Kategoris;
use App\Models\Menus;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;

class BrandaController extends Controller
{
    public function index()
    {   
        $kategoris = Kategoris::with('menus')->get();

        return view(
            'frontend.Menu.Branda',
            [
                'kategoris'      => $kategoris
            ]
        );
    }

   public function cart_add(Request $request)
{
    $request->validate([
        'id' => 'required|exists:menus,id',
        'qty' => 'required|integer|min:1|max:100',
        'note' => 'nullable|string|max:255',
        'varian' => 'nullable|string|max:100'
    ]);

    $menu = Menus::with('kategori')->findOrFail($request->id);

    $cart = session('cart', []);

    $qty = max(1, (int) $request->qty);
    $varian = $request->filled('varian') ? trim($request->varian) : null;
    $note = trim($request->note ?? '');

    if (strtolower(optional($menu->kategori)->nama_kategori) === 'minuman' && !$varian) {
        return response()->json([
            'success' => false,
            'message' => 'Pilih varian dulu'
        ], 422);
    }

    $row_id = md5($menu->id . '-' . strtolower($varian) . '-' . strtolower($note));

    if (isset($cart[$row_id])) {
        $cart[$row_id]['qty'] += $qty;
    } else {
        $cart[$row_id] = [
            'row_id' => $row_id,
            'id' => $menu->id,
            'nama' => $menu->nama_menu,
            'harga' => $menu->harga,
            'gambar' => $menu->gambar,
            'qty' => $qty,
            'varian' => $varian,
            'note' => $note,
        ];
    }

    session()->put('cart', $cart);

    $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

    return response()->json([
        'success' => true,
        'total_item' => collect($cart)->sum('qty'),
        'total' => $total,
        'html' => view('frontend.partials.cart_items', compact('cart'))->render()
    ]);
}

    public function show($id_menu, Request $request)
    {
        $menu = Menus::findOrFail($id_menu);

        $from = $request->query('from');
        $qty = max(1, (int) $request->query('qty', 1));

        return view('frontend.Menu.DetailMenu', compact('menu', 'from', 'qty'));
    }

    public function search(Request $request)
{
    $keyword = $request->keyword;

    $menus = Menus::where('nama', 'like', "%{$keyword}%")->get();

    return response()->json($menus);
}
}
