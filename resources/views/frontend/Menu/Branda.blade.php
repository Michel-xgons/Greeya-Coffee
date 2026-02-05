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
        <h6 class="bg-secondary text-white text-center fw-semibold rounded py-2 my-3 mx-auto" style="max-width:300px;">Minuman</h6>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
            <div class="col">
                <div class="card card-menu shadow-sm h-100">
                    <div class="position-relative" style="border-radius:0.375rem 0.375rem 0 0; aspect-ratio:1/1;">
                        <img src="{{ asset('images/ice_tea.jpg') }}" class="w-100 h-100" style="object-fit:cover;" alt="...">
                    </div>
                    <div class="card-body text-center p-3"
                        data-name="Ice Tea"
                        data-price="8000" data-qty="0">
                        <div class="fw-semibold mb-1">
                            Ice Tea
                        </div>
                        <div class="mb-2 fw-bold">Rp8.000</div>
        {{-- Tambahkan tombol keranjang --}}
                        <div class="d-flex justify-content-end mb-4 mt-2">
                            <button class="btn btn-outline-dark btn-sm w-100 rounded-pill add-to-cart" type="button">Tambah ke Keranjang</button>
                        </div>
                        <div class="quantity-control d-none mt-2 d-flex justify-content-center align-items-center gap-2">
                            <button class="btn btn-outline-dark btn-sm minus-btn" type="button">−</button>
                            <span class="fw-semibold quantity">0</span>
                            <button class="btn btn-outline-dark btn-sm plus-btn" type="button">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- menu makanan --}}
        <h6 class="bg-secondary text-white text-center fw-semibold rounded py-2 my-3 mx-auto" style="max-width:300px;">Makanan</h6>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mb-5">
            <div class="col">
                <div class="card card-menu shadow-sm h-100">
                    <div class="position-relative" style="background:#0073bf; border-radius:0.375rem 0.375rem 0 0; aspect-ratio:1/1;">
                        <img src="{{ asset('images/mie.jpg') }}" class="w-100 h-100" style="object-fit:cover;" alt="...">
                    </div>
                    <div class="card-body text-center p-3"
                        data-name="mie"
                        data-price="10000" data-qty="0">
                        <div class="fw-semibold mb-1">
                            mie
                        </div>
                        <div class="mb-2 fw-bold">Rp10.000</div>
        {{-- Tambahkan tombol keranjang --}}
                        <div class="d-flex justify-content-end mb-4 mt-2">
                            <button class="btn btn-outline-dark btn-sm w-100 rounded-pill add-to-cart" type="button">Tambah ke Keranjang</button>
                        </div>
                        <div class="quantity-control d-none mt-2 d-flex justify-content-center align-items-center gap-2">
                            <button class="btn btn-outline-dark btn-sm minus-btn" type="button">−</button>
                            <span class="fw-semibold quantity">0</span>
                            <button class="btn btn-outline-dark btn-sm plus-btn" type="button">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection