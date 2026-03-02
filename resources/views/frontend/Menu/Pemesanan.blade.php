@extends('frontend.layouts.main')
@section('title', 'Greeya Coffee')

@section('content')
    <div class="card p-3">
        <h5 class="text-center mb-3">Data Pemesan</h5>

        <form action="{{ route('invoice.create') }}" method="POST">
            @csrf
            <div class="mb-3">
                <strong>Nama</strong>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <strong>Email</strong>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <strong>Nomor Telepon</strong>
                <input type="text" name="telepon" class="form-control" required>
            </div>

            @if (session('nomor_meja'))
                <div
                    class="bg-warning bg-opacity-10 text-center fw-semibold rounded-4 py-2 mb-3 border border-warning-subtle">
                    <i class="fas fa-chair me-1 text-warning"></i>
                    Nomor Meja:
                    <span class="fw-bold">{{ session('nomor_meja') }}</span>
                </div>
            @else
                <div class="alert alert-danger text-center">
                    Nomor meja belum dipilih. Silakan scan QR meja terlebih dahulu.
                </div>
            @endif

            <button type="submit" class="btn btn-dark w-100 mt-3 rounded-pill">
                Simpan & Lanjut Pembayaran
            </button>
        </form>
    </div>

@endsection
