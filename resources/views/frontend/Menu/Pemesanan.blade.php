@extends('frontend.layouts.main')
@section('title', 'Greeya Coffee')

@section('content')
    <div class="card p-3">
        <h5 class="text-center mb-3">Data Pemesan</h5>

        <form id="formPemesanan">
            <div class="mb-3">
                <strong>Nama</strong>
                <input type="text" id="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <strong>Email</strong>
                <input type="email" id="email" class="form-control">
            </div>

            <div class="mb-3">
                <strong>Nomor Telepon</strong>
                <input type="text" id="telepon" class="form-control" required>
            </div>
        </form>
    </div>

    <div class="card mt-4 p-3">
        <div class="d-flex justify-content-between">
            <strong>Total Pembayaran</strong>
            <strong class="text-danger" id="grandTotal">Rp0</strong>
        </div>

        <button id="payNowBtn" class="btn btn-dark w-100 mt-3 rounded-pill">
            Simpan & Lanjutkan Pembayaran
        </button>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('greeya_cart') || '{}');

    const subtotal = cart.totalHarga || 0;
    const biayaLain = 170;
    const total = subtotal + biayaLain;

    document.getElementById('grandTotal').textContent =
        `Rp${total.toLocaleString('id-ID')}`;

    document.getElementById('payNowBtn').addEventListener('click', () => {
        const customer = {
            name: document.getElementById('nama').value.trim(),
            email: document.getElementById('email').value.trim(),
            phone: document.getElementById('telepon').value.trim()
        };

        if (!customer.name || !customer.phone) {
            alert('Nama dan Nomor Telepon wajib diisi');
            return;
        }

        localStorage.setItem('greeya_customer', JSON.stringify(customer));
        window.location.href = '/checkout';
    });
});
</script>

@endsection
