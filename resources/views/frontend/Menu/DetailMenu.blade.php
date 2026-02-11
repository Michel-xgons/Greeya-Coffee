@extends('frontend.layouts.main')
@section('title', 'Checkout')

@section('content')

    <div class="container-fluid p-0">

        <!-- GAMBAR PRODUK -->
        <div class="card bg-secondary">
            {{-- <img src="{{ asset('images/' . $menu->gambar_menu) }}" class="card-img-top" alt="..."> --}}
            <img src="{{ asset('images/ice_tea.jpg') }}" class="img-fluid w-50 mx-auto d-block" alt="...">
        </div>

        <!-- CARD KONTEN -->
        <div class="card rounded-top-4 mt-n3">
            <div class="card-body">
                <!-- NAMA & HARGA -->
                <h5 class="fw-bold mb-1">{{ $menu->nama_menu }}</h5>
                <div class="fw-semibold mb-3">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>

                <hr>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        HOT
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                        ICE
                    </label>
                </div>

                <hr>

                <!-- CATATAN -->
                <form action="{{ route('cart.updateNote', $menu->id_menu) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>

                    @if ($from == 'checkout')
                        <button type="submit" class="btn btn-dark">
                            Update Pesanan
                        </button>
                    @else
                        <button type="submit" class="btn btn-dark">
                            Tambah Pesanan
                        </button>
                    @endif


            </div>
        </div>
    </div>


@endsection
