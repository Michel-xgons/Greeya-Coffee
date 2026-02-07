@extends('frontend.layouts.main')
@section('title', 'Checkout')

@section('content')

    <div class="container my-4 pb-5">
        <h4 class="mb-3 text-center">Pesanan Kamu</h4>
        <div class="container">
            @foreach ($cart as $item)
                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title">{{ $item['name'] }}</h5>
                                <p class="card-text">Jumlah: {{ $item['qty'] }}</p>
                                <p class="card-text">Harga: Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                <p class="card-text">Subtotal:
                                    Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                                <p class="card-text">
                                    <button class="btn btn-sm btn-outline-primary"
                                        onclick="openNoteModal('{{ $item['id'] }}', '{{ $item['note'] ?? '' }}')">
                                        Tambah Catatan
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('Branda') }}" class="btn btn-outline-dark btn-sm rounded-pill">
                Tambah Pesanan
            </a>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Rincian Pembayaran</h5>
                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp0</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Biaya lainnya</span>
                    <span id="fee">Rp170</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span id="grandTotal">Rp0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FIXED BOTTOM -->
    <div class="fixed-bottom bg-light py-3 px-4 border-top">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold">Total Pembayaran</div>
                <div id="totalBayar" class="fs-5 text-danger fw-semibold">
                    Rp0
                </div>
            </div>
            <button class="btn btn-dark rounded-pill px-4 fw-bold" id="payNowBtn">
                Lanjut Pembayaran
            </button>
        </div>
    </div>

    <!-- MODAL CATATAN -->
    <div class="modal fade" id="noteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Catatan Lainnya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <textarea id="noteTextarea" class="form-control" rows="4" placeholder="Tambahkan catatan lain di sini"></textarea>
                    <input type="hidden" id="noteItemId">
                </div>

                <div class="modal-footer border-0">
                    <button class="btn btn-warning w-100 fw-bold" onclick="saveNote()">
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
