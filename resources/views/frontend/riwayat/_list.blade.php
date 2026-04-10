<div class="row justify-content-center">

    @if ($riwayat->isEmpty())

        <div class="col-12 col-md-6 text-center py-5">
            <div class="alert alert-secondary">
                Belum ada riwayat pesanan
            </div>
        </div>
    @else
        @foreach ($riwayat as $pesanan)
            @include('frontend.riwayat._card', ['pesanan' => $pesanan])
        @endforeach
    @endif

</div>

<div class="text-end mt-2">

    @if ($pesanan->payment_status == 'pending' && $pesanan->pembayaran)
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

    <!--  TAMBAHAN -->
    <a href="{{ route('pesan.lagi') }}" class="btn btn-outline-dark btn-sm">
        Pesan Lagi
    </a>

</div>
