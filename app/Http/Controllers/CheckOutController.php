<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        // 🔒 Proteksi cart kosong
        if (empty($cart)) {
            return redirect()->route('Branda')
                ->with('error', 'Keranjang masih kosong.');
        }

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return view('frontend.Menu.checkout', compact('cart', 'total'));
    }

    // 🔥 LOAD CART (UNTUK MODAL)
    public function cart()
    {
        $cart = session('cart', []);

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        $html = view('frontend.cart.items', compact('cart'))->render();

        return response()->json([
            'html' => $html,
            'total' => $total,
            'total_item' => collect($cart)->sum('qty')
        ]);
    }

    // 🔥 UPDATE QTY (FIXED)
    public function update(Request $request)
    {
        $request->validate([
            'row_id' => 'required',
            'action' => 'required|in:plus,minus'
        ]);

        $cart = session()->get('cart', []);
        $row_id = $request->row_id;

        if (!isset($cart[$row_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ]);
        }

        // 🔥 LOGIC BARU (SYNC DENGAN JS)
        if ($request->action === 'plus') {
            $cart[$row_id]['qty'] += 1;
        }

        if ($request->action === 'minus') {
            $cart[$row_id]['qty'] -= 1;
        }

        // 🔒 MINIMAL
        if ($cart[$row_id]['qty'] < 1) {
            unset($cart[$row_id]);
        }

        // 🔒 MAX
        if (isset($cart[$row_id]) && $cart[$row_id]['qty'] > 100) {
            $cart[$row_id]['qty'] = 100;
        }

        session()->put('cart', $cart);

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return response()->json([
            'success' => true,
            'html' => view('frontend.cart.items', compact('cart'))->render(),
            'total' => $total,
            'total_item' => collect($cart)->sum('qty')
        ]);
    }

    // 📝 NOTE
    public function note(Request $request)
    {
        $request->validate([
            'row_id' => 'required',
            'note' => 'nullable|string|max:255'
        ]);

        $cart = session()->get('cart', []);
        $row_id = $request->row_id;

        if (!isset($cart[$row_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        $note = trim($request->note);

        $cart[$row_id]['note'] = $note === '' ? null : $note;

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'note' => $cart[$row_id]['note']
        ]);
    }

    // 🗑️ DELETE ITEM (SUDAH FIX)
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $row_id = $request->row_id;

        if (isset($cart[$row_id])) {
            unset($cart[$row_id]);
        }

        session()->put('cart', $cart);

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return response()->json([
            'success' => true,
            'html' => view('frontend.cart.items', compact('cart'))->render(),
            'total' => $total,
            'total_item' => collect($cart)->sum('qty')
        ]);
    }

    // 🚀 PROCESS CHECKOUT
    // public function process()
    // {
    //     // 🔒 CEK CART
    //     if (empty(session('cart'))) {
    //         return redirect()->route('Branda')
    //             ->with('error', 'Keranjang kosong');
    //     }

    //     // 🔒 CEK CUSTOMER
    //     if (!session()->has('customer')) {
    //         return redirect()->route('Pemesanan')
    //             ->with('error', 'Silakan isi data pemesan');
    //     }

    //     // 🔥 NEXT STEP → PAYMENT
    //     return redirect()->route('pemesanan.simpan');
    // }
   public function auto()
{
    if (empty(session('cart'))) {
        return redirect()->route('Branda')
            ->with('error', 'Cart kosong');
    }

    if (!session()->has('customer_data')) {
        return redirect()->route('Pemesanan');
    }

    // 🔥 LANGSUNG PROSES PESANAN (TANPA LOOP)
   return redirect()->route('pemesanan.auto');
}
}