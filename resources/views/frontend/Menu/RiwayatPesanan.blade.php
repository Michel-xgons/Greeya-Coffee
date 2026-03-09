@extends('frontend.layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')

    <div class="container py-3">
        <h4 class="text-center fw-bold mb-4">
            Riwayat Pesanan Kamu
        </h4>

        <div class="row justify-content-center">
            @forelse($riwayat as $pesanan)
                <div class="col-md-7 mb-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            {{-- HEADER --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0 fw-bold">
                                        Pesanan Anda
                                    </h6>
                                    <small class="text-muted">
                                        {{ $pesanan->created_at->format('d M Y H:i') }}
                                    </small>
                                </div>

                                <div>
                                    @if ($pesanan->payment_status == 'paid')
                                        <span class="badge bg-success px-3 py-2">
                                            Lunas
                                        </span>
                                    @elseif($pesanan->payment_status == 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            Menunggu Bayar
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            {{-- LIST MENU --}}
                            @foreach ($pesanan->detailPesanans as $item)
                                <div class="d-flex align-items-center mb-3">
                                    {{-- Gambar menu --}}
                                    <div class="me-3">
                                        @if ($item->menu->gambar)
                                            <img src="{{ asset('storage/' . $item->menu->gambar) }}" width="60"
                                                height="60" class="rounded object-fit-cover">
                                        @endif
                                    </div>

                                    {{-- Nama menu --}}
                                    <div class="flex-grow-1">

                                        <div class="fw-semibold">
                                            {{ $item->menu->nama_menu }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        </small>
                                    </div>

                                    {{-- Subtotal --}}
                                    <div class="fw-bold">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            <hr>

                            {{-- TOTAL --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    Total Pembayaran
                                </h6>
                                <h5 class="fw-bold text-primary mb-0">
                                    Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                </h5>
                            </div>

                            {{-- BUTTON --}}
                            @if ($pesanan->payment_status != 'paid')
                                <div class="mt-3 d-grid">
                                    <form action="{{ route('pay.again', $pesanan->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary fw-semibold">
                                            Bayar Sekarang
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @empty

                <div class="col-md-6">
                    <div class="alert alert-secondary text-center">
                        Belum ada riwayat pesanan
                    </div>
                </div>

            @endforelse
        </div>
    </div>
@endsection
