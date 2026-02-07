@extends('frontend.layouts.main')
@section('title', 'greeya coffee')
@section('content')

    <div class="card shadow-sm mb-3">
        <div class="card-body p-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-bold">greeya coffee</h6>
                <small class="text-muted">Buka hari ini, 15:00–02:00</small>
            </div>
            <i class="fas fa-chevron-right text-muted"></i>
        </div>
    </div>

    <div class="bg-warning-subtle text-center fw-semibold rounded py-2 my-3 mx-auto" style="max-width:300px;">
        Nomor Meja: JDA6
    </div>

    <ul class="nav nav-tabs mt-3 px-2 overflow-auto flex-nowrap">
        <li class="nav-item">
            <a class="nav-link active fw-semibold text-uppercase" href="#">Makanan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-semibold text-uppercase" href="#">Minuman</a>
        </li>
    </ul>

    {{-- menu minuman --}}
    <h6 class="bg-secondary text-white text-center fw-semibold rounded py-2 my-3 mx-auto" style="max-width:300px;">Minuman
    </h6>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
        @foreach ($menuMinuman as $item)
            <div class="col">
                <div class="card card-menu shadow-sm h-100">

                    <form action="{{ route('cart.add') }}" method="POST" class="cart-form">
                        @csrf

                        <input type="hidden" name="id_menu" value="{{ $item->id_menu }}">
                        <input type="hidden" name="nama_menu" value="{{ $item->nama_menu }}">
                        <input type="hidden" name="harga" value="{{ $item->harga }}">
                        <input type="hidden" name="qty" value="1" class="qty-input">

                        <div class="card card-menu shadow-sm h-100">
                            <a href="{{ route('detail.menu', $item->id_menu) }}">
                                <img src="{{ asset('images/ice_tea.jpg') }}" class="w-100">
                            </a>

                            <div class="card-body text-center p-3">
                                <div class="fw-semibold mb-1">{{ $item->nama_menu }}</div>

                                <div class="mb-2 fw-bold">
                                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                                </div>

                                {{-- CONTROL QTY --}}
                                <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                                    <button type="button" class="btn btn-outline-dark btn-sm qty-minus">−</button>

                                    <span class="fw-bold qty-text">1</span>

                                    <button type="button" class="btn btn-outline-dark btn-sm qty-plus">+</button>
                                </div>

                                <button type="submit" class="btn btn-outline-dark btn-sm w-100 rounded-pill">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        @endforeach
    </div>

    {{-- menu makanan --}}
    <h6 class="bg-secondary text-white text-center fw-semibold rounded py-2 my-3 mx-auto" style="max-width:300px;">Makanan
    </h6>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
        @foreach ($menuMakanan as $item)
            <div class="col">
                <div class="card card-menu shadow-sm h-100">

                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_menu" value="{{ $item->id_menu }}">
                        <input type="hidden" name="nama_menu" value="{{ $item->nama_menu }}">
                        <input type="hidden" name="harga" value="{{ $item->harga }}">

                        <a href="{{ route('detail.menu', $item->id_menu) }}">
                            <img src="{{ asset('images/mie.jpg') }}" class="w-100">
                        </a>

                        <div class="card-body text-center p-3">
                            <div class="fw-semibold mb-1">{{ $item->nama_menu }}</div>
                            <div class="mb-2 fw-bold">
                                Rp{{ number_format($item->harga, 0, ',', '.') }}
                            </div>

                            <button type="submit" class="btn btn-outline-dark btn-sm w-100 rounded-pill">
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.cart-form').forEach(form => {

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const id    = form.querySelector('[name="id_menu"]').value;
            const name  = form.querySelector('[name="nama_menu"]').value;
            const price = parseInt(form.querySelector('[name="harga"]').value);
            const qty   = parseInt(form.querySelector('[name="qty"]').value || 1);

            fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({
                    id: id,
                    name: name,
                    price: price,
                    change: qty
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log('Cart updated:', data);
            });
        });

    });

});
</script>


