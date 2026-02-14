@extends('frontend.layouts.main')
@section('title', 'Detail Menu')

@section('content')

<div class="container-fluid p-0">

    <!-- GAMBAR PRODUK -->
    <div class="card bg-secondary">
        <img src="{{ asset('images/ice_tea.jpg') }}" class="img-fluid w-50 mx-auto d-block" alt="...">
    </div>

    <div class="card rounded-top-4 mt-n3">
        <div class="card-body">

            <h5 class="fw-bold mb-1">{{ $menu->nama_menu }}</h5>
            <div class="fw-semibold mb-3">
                Rp{{ number_format($menu->harga, 0, ',', '.') }}
            </div>

            <hr>

            {{-- FORM --}}
            @if ($from == 'checkout')
                <form action="{{ route('cart.updateNote', $menu->id_menu) }}" method="POST">
            @else
                <form action="{{ route('cart.add') }}" method="POST">
            @endif
                @csrf

                <input type="hidden" name="id" value="{{ $menu->id_menu }}">
                <input type="hidden" name="name" value="{{ $menu->nama_menu }}">
                <input type="hidden" name="price" value="{{ $menu->harga }}">

                <!-- VARIANT -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="variant" value="HOT">
                    <label class="form-check-label">HOT</label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="variant" value="ICE" checked>
                    <label class="form-check-label">ICE</label>
                </div>

                <hr>

                <!-- CATATAN -->
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="note" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-dark w-100">
                    {{ $from == 'checkout' ? 'Update Pesanan' : 'Tambah Pesanan' }}
                </button>

            </form>

        </div>
    </div>
</div>

@endsection
