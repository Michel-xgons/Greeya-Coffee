@extends('frontend.layouts.main')
@section('title', 'Checkout')

@section('content')

<div class="container my-4 pb-5">
    <h4 class="mb-3">Pesanan Kamu</h4>

    <div id="checkoutItems" class="mb-4">
        <p class="text-muted">Memuat pesanan...</p>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('Branda') }}"
        class="btn btn-outline-dark btn-sm rounded-pill">
            Tambah Pesanan
        </a>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Rincian Pembayaran</h5>
            <div class="d-flex justify-content-between">
                <span>Subtotal</span>
                <span id="subtotal">Rp0</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Biaya lainnya</span>
                <span id="fee">Rp170</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total</span>
                <span id="grandTotal">Rp0</span>
            </div>
        </div>
    </div>
</div>

<!-- FIXED BOTTOM -->
<div class="fixed-bottom bg-light py-3 px-4 border-top">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="fw-bold">Total Pembayaran</div>
            <div id="totalBayar" class="fs-5 text-danger fw-semibold">
                Rp0
            </div>
        </div>
        <button class="btn btn-dark rounded-pill px-4 fw-bold"
                id="payNowBtn">
            Lanjut Pembayaran
        </button>
    </div>
</div>

    <!-- MODAL CATATAN -->
    <div class="modal fade" id="noteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
        <div class="modal-header border-0">
            <h5 class="modal-title fw-bold">Catatan Lainnya</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <textarea id="noteTextarea"
                    class="form-control"
                    rows="4"
                    placeholder="Tambahkan catatan lain di sini"></textarea>
            <input type="hidden" id="noteItemId">
        </div>

        <div class="modal-footer border-0">
            <button class="btn btn-warning w-100 fw-bold"
                    onclick="saveNote()">
                Tambah
            </button>
        </div>
        </div>
    </div>
    </div>

@endsection

<script>
let noteModal;

document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('greeya_cart')) || { items: [] };
    const container = document.getElementById('checkoutItems');

    noteModal = new bootstrap.Modal(
        document.getElementById('noteModal')
    );

    if (cart.items.length === 0) {
        container.innerHTML =
            '<p class="text-muted">Keranjang kamu kosong.</p>';
        return;
    }

    container.innerHTML = cart.items.map(item => `
        <div class="border-bottom py-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="fw-semibold">${item.name}</div>
                    <div class="text-muted small">
                        ${item.qty} x Rp${item.price.toLocaleString('id-ID')}
                    </div>

                    <div class="text-muted small mt-1">
                        ${item.note || 'Belum menambah catatan'}
                    </div>

                    <button class="btn btn-link p-0 text-warning small"
                            onclick="openNoteModal(${item.id})">
                        ✏ Tambah catatan lainnya
                    </button>
                </div>

                <div class="fw-bold">
                    Rp${(item.qty * item.price).toLocaleString('id-ID')}
                </div>
            </div>
        </div>
    `).join('');

    const subtotal = cart.totalHarga || 0;
    const fee = 170;
    const total = subtotal + fee;

    document.getElementById('subtotal').textContent =
        `Rp${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('fee').textContent =
        `Rp${fee.toLocaleString('id-ID')}`;
    document.getElementById('grandTotal').textContent =
        `Rp${total.toLocaleString('id-ID')}`;
    document.getElementById('totalBayar').textContent =
        `Rp${total.toLocaleString('id-ID')}`;

});
</script>

<script>
function openNoteModal(itemId) {
    const cart = JSON.parse(localStorage.getItem('greeya_cart')) || { items: [] };
    const item = cart.items.find(i => i.id == itemId);

    document.getElementById('noteTextarea').value =
        item?.note || '';
    document.getElementById('noteItemId').value = itemId;

    noteModal.show();
}

function saveNote() {
    const note = document.getElementById('noteTextarea').value.trim();
    const itemId = document.getElementById('noteItemId').value;

    const cart = JSON.parse(localStorage.getItem('greeya_cart')) || { items: [] };

    cart.items = cart.items.map(item => {
        if (item.id == itemId) {
            item.note = note;
        }
        return item;
    });

    localStorage.setItem('greeya_cart', JSON.stringify(cart));

    noteModal.hide();
    location.reload();
}
</script>
