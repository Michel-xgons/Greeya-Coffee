@extends('frontend.layouts.main')
@section('title', 'Checkout')

@section('content')

<div class="container-fluid p-0">

    <!-- GAMBAR PRODUK -->
    <div class="bg-secondary py-3 text-center position-relative">
        <img src="{{ asset('images/ice_tea.jpg') }}" class="w-50 h-25" style="object-fit:cover;" alt="...">
    </div>

    <!-- CARD KONTEN -->
    <div class="card rounded-top-4 mt-n3">
        <div class="card-body">

            <!-- NAMA & HARGA -->
            <h5 class="fw-bold mb-1">{{ $menu->nama_menu }}</h5>
            <div class="fw-semibold mb-3">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>

            <hr>

            <!-- HOT / ICE -->
            <div class="mb-3">
                <div class="fw-semibold">HOT ICE</div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="temperature" id="hot">
                    <label class="form-check-label" for="hot">
                        HOT
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="temperature" id="ice">
                    <label class="form-check-label" for="ice">
                        ICE
                    </label>
                </div>
            </div>

            <hr>

            <!-- CATATAN -->
            <div class="mb-4">
                <label class="fw-semibold mb-1">Catatan</label>
                <textarea class="form-control" rows="3" placeholder="Tambahkan catatan di sini"></textarea>
            </div>
        </div>
    </div>
</div>


@endsection