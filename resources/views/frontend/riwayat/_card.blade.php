<div class="col-12 col-md-8 col-lg-6 mb-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h6 class="mb-0 fw-bold">Pesanan Anda</h6>
                    <small class="text-muted">
                        {{ $pesanan->created_at->format('d M Y H:i') }}
                    </small>
                </div>

                <div>
                    @php
                        $status = $pesanan->pembayaran->transaction_status ?? null;
                    @endphp

                    @php
                        $status = strtolower($pesanan->pembayaran->transaction_status ?? '');
                    @endphp

                    @if ($status == 'paid')
                        <span class="badge bg-success">Lunas</span>
                    @elseif($status == 'pending')
                        <span class="badge bg-warning text-dark">Menunggu Bayar</span>
                    @elseif($status == 'expired')
                        <span class="badge bg-danger">Kadaluarsa</span>
                    @else
                        <span class="badge bg-secondary">Status Tidak Diketahui</span>
                    @endif
                </div>
            </div>

            <hr>

            @foreach ($pesanan->detailPesanans as $item)
                <div class="d-flex flex-column flex-md-row align-items-md-center mb-3">
                    <div class="me-3">
                        @if ($item->menu && $item->menu->gambar)
                            <img src="{{ asset('storage/' . $item->menu->gambar) }}" width="60" height="60"
                                class="rounded object-fit-cover">
                        @endif
                    </div>

                    <div class="flex-grow-1">
                        <div class="fw-semibold">
                            {{ $item->menu->nama_menu }}
                        </div>
                        <small class="text-muted">
                            {{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </small>
                    </div>

                    <div class="fw-bold mt-2 mt-md-0">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach

            <hr>

            <div class="d-flex justify-content-between">
                <h6>Total Pembayaran</h6>
                <h5 class="fw-bold text-primary">
                    Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                </h5>
            </div>

            <hr>

            <div class="text-end">
                @if ($status == 'pending' && $pesanan->pembayaran)
                    <a href="{{ $pesanan->pembayaran->invoice_url }}" class="btn btn-warning btn-sm">
                        Bayar Sekarang
                    </a>
                @elseif ($pesanan->payment_status == 'expired')
                    <form action="{{ route('pay.again', $pesanan->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-primary btn-sm">
                            Bayar Lagi
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</div>
