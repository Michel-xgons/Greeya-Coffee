@extends('frontend.layouts.main')
@section('title', 'greeya coffee')
@section('content')

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1 fw-bold text-capitalize">Greeya Coffee</h6>
                <small class="text-muted d-flex align-items-center gap-1">
                    <i class="fas fa-clock"></i>
                    Buka hari ini, 15:00 – 02:00
                </small>
            </div>
            <div class="text-muted">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>

    <div class="bg-warning bg-opacity-10 text-center fw-semibold rounded-4 py-2 my-3 mx-auto border border-warning-subtle"
        style="max-width: 300px;">
        <i class="fas fa-chair me-1 text-warning"></i>
        Nomor Meja: <span class="fw-bold">JDA6</span>
    </div>

    <nav>
        <div class="nav nav-tabs col-sm-12" id="nav-tab" role="tablist">

            @foreach ($kategoris as $kategori)
                <a class="nav-link fs-6 {{ $loop->first ? 'active' : '' }}" id="nav-{{ $kategori->id_kategori }}-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-{{ $kategori->id_kategori }}" role="tab"
                    aria-controls="nav-{{ $kategori->id_kategori }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">

                    {{ $kategori->nama_kategori }}

                </a>
            @endforeach


        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">Warning! {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @foreach ($kategoris as $kategori)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="nav-{{ $kategori->id_kategori }}"
                role="tabpanel" aria-labelledby="nav-{{ $kategori->id_kategori }}-tab">

                @if ($kategori->menus->count() > 0)
                    <div class="row mt-4">

                        @foreach ($kategori->menus as $item)
                            <div class="col-6 col-md-3 col-lg-3">

                                <div class="product-item">

                                    <figure>
                                        <a href="{{ route('detail.menu', $item->id_menu) }}">

                                            <img src="{{ asset('storage/' . $item->foto) }}"
                                                style="width: 100%; height: 100px; object-fit:cover;" class="tab-image">

                                        </a>
                                    </figure>

                                    <h3 class="mt-0 mb-2">{{ $item->nama_menu }}</h3>

                                    <h6 class="mt-2 mb-2">
                                        {{ 'Rp.' . number_format($item->harga) }}
                                    </h6>

                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf

                                        <input type="hidden" name="id" value="{{ $item->id_menu }}">
                                        <input type="hidden" name="nama" value="{{ $item->nama_menu }}">
                                        <input type="hidden" name="harga" value="{{ $item->harga }}">

                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-3 mt-3">

                                                <div class="input-group product-qty">

                                                    <span class="input-group-btn">
                                                        <button type="button"
                                                            class="quantity-left-minus btn btn-danger btn-number"
                                                            data-type="minus">
                                                            −
                                                        </button>
                                                    </span>

                                                    <input type="text" name="qty" class="form-control input-number"
                                                        value="1">

                                                    <span class="input-group-btn">
                                                        <button type="button"
                                                            class="quantity-right-plus btn btn-primary btn-number"
                                                            data-type="plus">
                                                            +
                                                        </button>
                                                    </span>

                                                </div>

                                                <small>Max:10</small>
                                            </div>
                                            <button type="submit" class="col-12 btn btn-outline-primary btn-sm">
                                                Tambah
                                            </button>

                                        </div>
                                    </form>

                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="col mb-5 mt-5">
                        <div class="text-center mt-5">
                            <img src="{{ asset('images/notfound.png') }}" width="10%" alt="not found">
                            <h6 class="text-muted mt-3">
                                <b>Tidak ditemukan!</b>
                            </h6>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.product-qty').forEach(function(qtyGroup) {

                const minusBtn = qtyGroup.querySelector('.quantity-left-minus');
                const plusBtn = qtyGroup.querySelector('.quantity-right-plus');
                const qtyInput = qtyGroup.querySelector('input[name="qty"]');

                minusBtn.addEventListener('click', function() {
                    let value = parseInt(qtyInput.value);

                    if (value > 1) {
                        qtyInput.value = value - 1;
                    }
                });

                plusBtn.addEventListener('click', function() {
                    let value = parseInt(qtyInput.value);

                    if (value < 10) {
                        qtyInput.value = value + 1;
                    }
                });

            });

        });
    </script>






    {{-- <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
        @foreach ($menuMinuman as $item)
            <div class="col">
                <div class="card card-menu shadow-sm h-100">
                    <div class="position-relative" style="border-radius:0.375rem 0.375rem 0 0; aspect-ratio:1/1;">
                        <a href="{{ route('detail.menu', $item->id_menu) }}">
                            <img src="{{ asset('images/ice_tea.jpg') }}" class="w-100 h-100" style="object-fit:cover;"
                                alt="...">
                        </a>
                    </div>

                    <div class="card-body text-center p-3" data-name="{{ $item->nama_menu }}"
                        data-price="{{ $item->harga }}" data-qty="{{ $item->jumlah }}">
                        <div class="fw-semibold mb-1">
                            {{ $item->nama_menu }}
                        </div>
                        <div class="mb-2 fw-bold">Rp{{ number_format($item->harga, 0, ',', '.') }}</div>
                        <div class="d-flex justify-content-end mb-4 mt-2">
                            <button class="btn btn-outline-dark btn-sm w-100 rounded-pill add-to-cart" type="button">Tambah
                                ke
                                Keranjang</button>
                        </div>
                        <div class="quantity-control d-none mt-2 d-flex justify-content-center align-items-center gap-2">
                            <button class="btn btn-outline-dark btn-sm minus-btn" type="button">−</button>
                            <span class="fw-semibold quantity">0</span>
                            <button class="btn btn-outline-dark btn-sm plus-btn" type="button">+</button>
                        </div>
                        </form>


                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
        @foreach ($menuMakanan as $item)
            <div class="col">
                <div class="card card-menu shadow-sm h-100">
                    <a href="{{ route('detail.menu', $item->id_menu) }}">
                        <div class="position-relative"
                            style="background:#0073bf; border-radius:0.375rem 0.375rem 0 0; aspect-ratio:1/1;">
                            <img src="{{ asset('images/mie.jpg') }}" class="w-100 h-100" style="object-fit:cover;"
                                alt="...">
                        </div>
                    </a>

                    <div class="card-body text-center p-3" data-name="{{ $item->nama_menu }}"
                        data-price="{{ $item->harga }}" data-qty="0">
                        <div class="fw-semibold mb-1">
                            {{ $item->nama_menu }}
                        </div>
                        <div class="mb-2 fw-bold">Rp{{ number_format($item->harga, 0, ',', '.') }}</div>
                        <div class="d-flex justify-content-end mb-4 mt-2">
                            <button class="btn btn-outline-dark btn-sm w-100 rounded-pill add-to-cart" type="button">Tambah
                                ke Keranjang</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}

@endsection
