@extends('frontend.layouts.main')
@section('title', 'Checkout')

@section('content')

    <div class="container my-4 pb-5">
        <h4 class="mb-3 text-center">Pesanan Kamu</h4>

        <div id="checkoutItems" class="mb-4">
            <p class="text-muted">Memuat pesanan...</p>
        </div>


        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('Branda') }}" class="btn btn-outline-dark btn-sm rounded-pill">
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
            <button class="btn btn-dark rounded-pill px-4 fw-bold" id="payNowBtn">
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
                    <textarea id="noteTextarea" class="form-control" rows="4" placeholder="Tambahkan catatan lain di sini"></textarea>
                    <input type="hidden" id="noteItemId">
                </div>

                <div class="modal-footer border-0">
                    <button class="btn btn-warning w-100 fw-bold" onclick="saveNote()">
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
        renderCheckout();
    });

    function renderCheckout() {
        const cart = JSON.parse(localStorage.getItem('greeya_cart')) || {
            items: []
        };
        const container = document.getElementById('checkoutItems');

        noteModal = new bootstrap.Modal(
            document.getElementById('noteModal')
        );

        if (!cart.items.length) {
            container.innerHTML = '<p class="text-muted">Keranjang kamu kosong.</p>';
            setTotal(0);
            return;
        }

        container.innerHTML = cart.items.map((item, index) => `
    <div class="border-bottom py-3 d-flex justify-content-between align-items-center">

        <!-- KIRI -->
        <div class="flex-grow-1">
            <div class="fw-semibold">${item.name}</div>

            <div class="fw-bold small">
                ${item.qty}x ${item.variant ?? ''}
            </div>

            <div class=" fw-bold  small fst-italic">
                ${item.note || 'Belum menambah catatan'}
            </div>

            <button class="btn btn-link p-0  small"
                    onclick="openNoteModal(${index})">
                ${item.note ? 'Ubah Catatan' : 'Tambah Catatan'}
            </button>

            <div class="fw-bold mt-1">
                Rp${(item.qty * item.price).toLocaleString('id-ID')}
            </div>
        </div>

        <!-- KANAN -->
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-dark btn-sm"
                    onclick="updateQty(${index}, -1)">−</button>

            <span class="fw-semibold">${item.qty}</span>

            <button class="btn btn-outline-dark btn-sm"
                    onclick="updateQty(${index}, 1)">+</button>
        </div>

    </div>
`).join('');



        const subtotal = cart.items.reduce((sum, item) => {
            return sum + (item.qty * item.price);
        }, 0);

        setTotal(subtotal);
    }


    // UPDATE QTY (+ / -)
    function updateQty(index, change) {
        const cart = JSON.parse(localStorage.getItem('greeya_cart')) || {
            items: []
        };

        if (!cart.items[index]) return;

        cart.items[index].qty += change;

        if (cart.items[index].qty <= 0) {
            cart.items.splice(index, 1);
        }

        localStorage.setItem('greeya_cart', JSON.stringify(cart));
        renderCheckout();
    }


    // CATATAN
    function openNoteModal(index) {
        const cart = JSON.parse(localStorage.getItem('greeya_cart')) || {
            items: []
        };
        const item = cart.items[index];

        document.getElementById('noteTextarea').value = item?.note || '';
        document.getElementById('noteItemId').value = index;

        noteModal.show();
    }

    function saveNote() {
        const note = document.getElementById('noteTextarea').value.trim();
        const index = document.getElementById('noteItemId').value;

        const cart = JSON.parse(localStorage.getItem('greeya_cart')) || {
            items: []
        };

        if (cart.items[index]) {
            cart.items[index].note = note;
        }

        localStorage.setItem('greeya_cart', JSON.stringify(cart));

        noteModal.hide();
        renderCheckout(); // ⬅ render ulang TANPA reload
    }



    //TOTAL

    function setTotal(subtotal) {
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
    }
</script>
