@extends('frontend.layouts.main')
@section('title', 'Greeya Coffee')

@section('content')
<div class="card p-3">
    <h5 class="text-center mb-3">Data Pemesan</h5>

    <form action="{{ route('pemesanan.simpan') }}" method="POST">
        @csrf

        <div class="mb-3">
            <strong>Nama</strong>
            <input type="text" name="customer[name]" class="form-control"
    value="{{ session('customer_data.name') }}">
        </div>

        <div class="mb-3">
            <strong>Email</strong>
            <input type="email" name="customer[email]" class="form-control"
    value="{{ session('customer_data.email') }}">
        </div>

        <div class="mb-3">
            <strong>Nomor Telepon</strong>
            <input type="text" name="customer[phone]" class="form-control"
    value="{{ session('customer_data.phone') }}">
        </div>

        @if (session('nomor_meja'))
            <div class="bg-warning bg-opacity-10 text-center fw-semibold rounded-4 py-2 mb-3 border border-warning-subtle">
                Nomor Meja:
                <span class="fw-bold">{{ session('nomor_meja') }}</span>
            </div>
        @endif

        <button type="submit"
                onclick="console.log('CLICK KEDETECT')"
                class="btn btn-dark w-100 mt-3 rounded-pill">
            Simpan & Lanjut Pembayaran
        </button>

    </form>
</div>
@endsection