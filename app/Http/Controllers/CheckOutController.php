<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        // Proteksi cart kosong
        if (empty($cart)) {
            return redirect()->route('Branda')
                ->with('error', 'Keranjang masih kosong.');
        }

        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return view('frontend.Menu.checkout', compact('cart', 'total'));
    }


    public function get()
    {
        return response()->json(session('cart', []));
    }

    public function update(Request $request)
    {
        // VALIDASI REQUEST
        $request->validate([
            'row_id' => 'required',
            'change' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);

        $row_id = $request->row_id;
        $change = (int) $request->change;

        if (isset($cart[$row_id])) {

            $cart[$row_id]['qty'] += $change;

            if ($cart[$row_id]['qty'] <= 0) {
                unset($cart[$row_id]);
            }

            session()->put('cart', $cart);
        }

        // hitung ulang total
        $grandTotal = 0;

        foreach ($cart as $item) {
            $grandTotal += $item['harga'] * $item['qty'];
        }

        return response()->json([
            'success' => true,
            'total' => $grandTotal,
            'cart' => $cart
        ]);
    }

    public function note(Request $request)
    {
        $cart = session()->get('cart', []);

        $row_id = $request->row_id;

        if (isset($cart[$row_id])) {
            $cart[$row_id]['note'] = $request->note;
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        $row_id = $request->row_id;

        if (isset($cart[$row_id])) {
            unset($cart[$row_id]);
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    public function process()
    {
        $cart = session('cart', []);

        // Cek cart kosong
        if (empty($cart)) {
            return redirect()
                ->route('Branda')
                ->with('error', 'Keranjang kosong, silakan pilih pesanan.');
        }

        // Cek customer sudah isi atau belum
        if (!session()->has('customer')) {
            return redirect()->route('Pemesanan');
        }

        // Kalau sudah pernah isi
        return redirect()->route('payment'); // nanti Xendit
    }
}
