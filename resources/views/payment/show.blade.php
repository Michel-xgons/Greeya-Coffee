@extends('frontend.layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg border-0" style="max-width: 500px; width: 100%; border-radius: 15px;">

        <div class="card-header text-center bg-dark text-white">
            <h4 class="mb-0">Status Pembayaran Anda</h4>
        </div>

        <div class="card-body p-4">

            <div class="mb-3">
                <strong>Kode Pesanan:</strong>
                <div class="text-muted">{{ $pesanan->kode_pesanan }}</div>
            </div>

            <div class="mb-3">
                <strong>Waktu Pesanan:</strong>
                <div class="text-muted">{{ $pesanan->waktu_pesan }}</div>
            </div>

            <div class="mb-3">
                <strong>Nama:</strong>
                <div class="text-muted">{{ $pesanan->customer?->name ?? '-' }}</div>
            </div>

            <div class="mb-3">
                <strong>Total Bayar:</strong>
                <h5 class="text-primary">Rp {{ number_format($pesanan->total_harga) }}</h5>
            </div>

            @php
                $status = $pesanan->payment_status;
            @endphp

            <div class="mb-3">
                <strong>Status:</strong><br>

                <span id="status-text"
                    class="badge px-3 py-2
                    {{ $status == 'pending' ? 'bg-warning text-dark' : '' }}
                    {{ $status == 'paid' ? 'bg-success' : '' }}">

                    {{ $status == 'pending' ? 'Menunggu Pembayaran' : 'Sudah Dibayar' }}
                </span>
            </div>

            {{-- Tombol bayar --}}
            @if ($status == 'pending' && $pesanan->pembayaran)
                <div class="d-grid mb-3">
                    <a href="{{ $pesanan->pembayaran->invoice_url }}" target="_blank" class="btn btn-primary btn-lg">
                        Bayar Sekarang
                    </a>
                </div>
            @endif

        </div>

        <div class="card-footer text-center text-muted small">
            Sistem akan memperbarui status otomatis

            <div id="loading-text" class="text-muted small">
                Mengecek status pembayaran...
            </div>
        </div>

    </div>
</div>

<script>
    let isPaid = false;

    const interval = setInterval(() => {

        fetch("{{ url('/cek-status/' . $pesanan->id) }}")
            .then(res => res.json())
            .then(data => {

                const statusText = document.getElementById('status-text');
                const loadingText = document.getElementById('loading-text');

                if (data.status === 'paid' && !isPaid) {

                    isPaid = true;
                    clearInterval(interval); // stop polling

                    // Update UI
                    statusText.innerHTML = "Sudah Dibayar";
                    statusText.className = "badge bg-success px-3 py-2";

                    if (loadingText) {
                        loadingText.style.display = 'none';
                    }

                    // Delay biar user lihat perubahan
                    setTimeout(() => {

                        fetch("{{ route('cart.destroy') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(() => {
                            window.location.href = "{{ route('riwayat.pesanan') }}";
                        })
                        .catch(() => {
                            console.error('Gagal hapus cart');
                            window.location.href = "{{ route('riwayat.pesanan') }}";
                        });

                    }, 1500);
                }

            })
            .catch(err => {
                console.error('Error cek status:', err);
            });

    }, 3000);
</script>

@endsection