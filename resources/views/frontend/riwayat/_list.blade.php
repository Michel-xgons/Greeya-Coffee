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

<!-- tombol global -->
<div class="text-end mt-3">
    <a href="{{ route('pesan.lagi') }}" class="btn btn-outline-dark btn-sm">
        Pesan Lagi
    </a>
</div>