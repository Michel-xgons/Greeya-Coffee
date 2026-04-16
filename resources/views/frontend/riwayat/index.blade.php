@extends('frontend.layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')

    <div class="container py-3">
        <input type="hidden" id="phone" value="{{ $phone }}">
        <!-- FILTER -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4 filter-btn">
            <button onclick="filterStatus(event, 'all')" class="btn btn-outline-secondary active">
                Semua
            </button>
            <button onclick="filterStatus(event, 'paid')" class="btn btn-outline-success">
                Lunas
            </button>
            <button onclick="filterStatus(event, 'pending')" class="btn btn-outline-warning">
                Pending
            </button>
            <button onclick="filterStatus(event, 'expired')" class="btn btn-outline-danger">
                Kadaluarsa
            </button>
        </div>

        <!-- TITLE -->
        <h4 class="text-center fw-bold mb-4">
            Riwayat Pesanan Kamu
        </h4>

        <!-- CONTENT -->
        <div id="riwayat-container">
            @include('frontend.riwayat._list', ['riwayat' => $riwayat])
        </div>

    </div>


    <script>
    function filterStatus(e, status) {

        // reset active
        document.querySelectorAll('.filter-btn button')
            .forEach(btn => btn.classList.remove('active'));

        e.target.classList.add('active');

        const phone = document.getElementById('phone').value;

        // loading state
        document.getElementById('riwayat-container').innerHTML =
            `<div class="text-center py-3 text-muted">Loading...</div>`;

        fetch('/riwayat/data?status=' + status + '&phone=' + phone)
            .then(res => {
                if (!res.ok) throw new Error('Server error');
                return res.json();
            })
            .then(res => {
                document.getElementById('riwayat-container').innerHTML = res.html;
            })
            .catch(err => {
                console.error(err);
                document.getElementById('riwayat-container').innerHTML =
                    `<div class="text-center text-danger py-3">Gagal memuat data</div>`;
            });
    }
</script>

@endsection
