@extends('frontend.layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')

    <div class="d-flex gap-2 mb-3 filter-btn">
        <button onclick="filterStatus(event, 'all')" class="btn btn-secondary">Semua</button>
        <button onclick="filterStatus(event, 'paid')" class="btn btn-success">Lunas</button>
        <button onclick="filterStatus(event, 'pending')" class="btn btn-warning">Pending</button>
        <button onclick="filterStatus(event, 'expired')" class="btn btn-danger">Kadaluarsa</button>
    </div>

    <div class="container py-3">
        <h4 class="text-center fw-bold mb-4">
            Riwayat Pesanan Kamu
        </h4>

        <div id="riwayat-container">
            @include('frontend.riwayat._list', ['riwayat' => $riwayat])
        </div>
    </div>


    <script>
        function filterStatus(e, status) {
            document.querySelectorAll('.filter-btn button')
                .forEach(btn => btn.classList.remove('active'));

            e.target.classList.add('active');

            fetch('/riwayat/data?status=' + status)
                .then(res => res.json())
                .then(res => {
                    document.getElementById('riwayat-container').innerHTML = res.html;
                });
        }
    </script>

@endsection
