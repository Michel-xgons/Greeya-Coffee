@extends('frontend.layouts.main')
@section('title', 'Detail Menu')

@section('content')

    <div class="container-fluid p-0">

        <!-- GAMBAR PRODUK -->
        <div class="card bg-secondary">
            <img src="{{ asset('storage/' . $menu->gambar) }}" class="img-fluid w-50 mx-auto d-block" alt="...">
        </div>

        <div class="card rounded-top-4 mt-n3">
            <div class="card-body">

                <h5 class="fw-bold mb-1">{{ $menu->nama_menu }}</h5>
                <div class="fw-semibold mb-3">
                    Rp{{ number_format($menu->harga, 0, ',', '.') }}
                </div>
                <div class="fw-semibold mb-2">{{ $menu->deskripsi }}
                </div>

                <hr>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf

                    <input type="hidden" name="id" value="{{ $menu->id }}">
                    <input type="hidden" name="nama" value="{{ $menu->nama_menu }}">
                    <input type="hidden" name="harga" value="{{ $menu->harga }}">


                    <!-- variant -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="varian" value="HOT">
                        <label class="form-check-label">HOT</label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="varian" value="ICE" checked>
                        <label class="form-check-label">ICE</label>
                    </div>

                    <!-- qty -->
                    <input type="hidden" name="qty" value="1">

                    <!-- note -->
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        Tambah Pesanan
                    </button>
                </form>


            </div>
        </div>
    </div>

@endsection
