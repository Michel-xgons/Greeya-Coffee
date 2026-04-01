@extends('frontend.layouts.main')
@section('title', 'Checkout')

@section('content')

    <div class="container my-4 pb-5">
        <h4 class="mb-3 text-center">Pesanan Kamu</h4>

        @if (empty($cart) || count($cart) == 0)

            <div class="text-center py-5">
                <p class="text-muted mb-3">Belum ada pesanan</p>
                <a href="{{ route('Branda') }}" class="btn btn-primary">
                    Pesan Sekarang
                </a>
            </div>
        @else
            <div class="row justify-content-center">
                @foreach ($cart as $item)
                    <div class="col-12 col-sm-10 col-md-8 col-lg-6" id="item-{{ $item['row_id'] }}">
                        <div class="card mb-3 shadow-sm border-0 rounded-4" data-harga="{{ $item['harga'] }}">
                            <div class="row g-0">

                                <div class="col-12 col-md-4">
                                    @if (isset($item['gambar']))
                                        <img src="{{ asset('storage/' . $item['gambar']) }}"
                                            class="img-fluid w-100 h-100 object-fit-cover rounded-start"
                                            style="max-height: 150px;" alt="...">
                                    @endif
                                </div>

                                <div class="col-12 col-md-8">
                                    <div class="card-body py-3">

                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                            <h5 class="mb-1">{{ $item['nama'] }}</h5>
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <button onclick="confirmRemove('{{ $item['row_id'] }}')"
                                                    class="btn btn-sm btn-outline-danger rounded-circle p-2">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <div class="d-flex align-items-center border rounded-pill px-2">
                                                    <button class="btn btn-sm border-0 btn-qty"
                                                        onclick="updateQty('{{ $item['row_id'] }}', -1)">−</button>
                                                    <span class="px-2 qty">{{ $item['qty'] }}</span>
                                                    <button class="btn btn-sm border-0 btn-qty"
                                                        onclick="updateQty('{{ $item['row_id'] }}', 1)">+</button>
                                                </div>
                                            </div>
                                        </div>

                                        @if (!empty($item['varian']))
                                            <div class="small text-muted">
                                                Varian: {{ $item['varian'] }}
                                            </div>
                                        @endif

                                        <div class="small text-muted">
                                            Harga: Rp{{ number_format($item['harga'], 0, ',', '.') }}
                                        </div>

                                        <div class="fw-bold text-dark mb-2 subtotal">
                                            Subtotal: Rp{{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                                        </div>

                                        @if (!empty($item['note']))
                                            <div class="small bg-light rounded p-2 mb-2 note">
                                                <strong>Catatan:</strong> {{ $item['note'] }}
                                            </div>
                                        @endif

                                        <button class="btn btn-outline-primary btn-sm"
                                            onclick="openNoteModal('{{ $item['row_id'] }}','{{ $item['note'] ?? '' }}')">
                                            Tambah Catatan
                                        </button>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif



        @if (!empty($cart) && count($cart) > 0)
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('Branda') }}" class="btn btn-outline-secondary">
                    Tambah Pesanan
                </a>
            </div>
        @endif

        <div class="card mb-5 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Rincian Pembayaran</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Biaya lainnya</span>
                    <span id="fee">Rp{{ number_format(4000, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span id="grandTotal">{{ 'Rp' . number_format($total + 4000, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FIXED BOTTOM -->
    <div class="fixed-bottom bg-white py-3 px-3 border-top shadow-lg">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                <div class="fw-bold">Total Pembayaran</div>
                <div id="totalBayar" class="fs-5 text-danger fw-semibold">
                    {{ 'Rp' . number_format($total + 4000, 0, ',', '.') }}
                </div>
            </div>
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold w-100 w-md-auto">
                    Lanjut Pembayaran
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL CATATAN -->
    <div class="modal fade" id="noteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <textarea id="noteTextarea" class="form-control" rows="4" placeholder="Tambahkan catatan untuk pesanan"></textarea>
                    <input type="hidden" id="noteItemId">
                </div>

                <div class="modal-footer border-0">
                    <button id="btnSaveNote" class="btn btn-warning w-100 fw-bold" onclick="saveNote()">
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        function removeItem(row_id) {
            fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        row_id: row_id
                    })
                })
                .then(res => res.json())
                .then(data => {

                    let el = document.getElementById('item-' + row_id);
                    if (!el) return;

                    el.style.transition = '0.3s';
                    el.style.opacity = 0;

                    setTimeout(() => {
                        el.remove();
                        updateTotal();

                        if (document.querySelectorAll('[data-harga]').length === 0) {
                            location.reload();
                        }
                    }, 300);

                })
                .catch(err => {
                    console.error('Error remove:', err);
                    showAlert('Gagal menghapus item', 'error');
                });
        }
    </script>

    <script>
        function updateQty(row_id, change) {

            let item = document.getElementById('item-' + row_id);
            if (!item) return;

            let qtyEl = item.querySelector('.qty');
            let currentQty = parseInt(qtyEl.innerText);

            if (currentQty + change < 1) return;

            fetch("{{ route('cart.update') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        row_id: row_id,
                        change: change
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {

                        let subtotalEl = item.querySelector('.subtotal');
                        let harga = parseInt(item.querySelector('.card').dataset.harga);

                        let newQty = currentQty + change;
                        // animasi keluar
                        qtyEl.classList.add('qty-animate');

                        // delay sedikit biar efek terasa
                        setTimeout(() => {
                            qtyEl.innerText = newQty;

                            // animasi masuk balik normal
                            qtyEl.classList.remove('qty-animate');
                        }, 100);

                        let qtyButtons = item.querySelectorAll('.btn-qty');

                        qtyButtons.forEach(btn => {
                            btn.disabled = true;
                            btn.style.opacity = 0.5;
                        });

                        setTimeout(() => {
                            qtyButtons.forEach(btn => {
                                btn.disabled = false;
                                btn.style.opacity = 1;
                            });
                        }, 300);

                        let subtotal = harga * newQty;
                        subtotalEl.classList.add('fade-update');

                        setTimeout(() => {
                            subtotalEl.innerText = 'Subtotal: Rp' + subtotal.toLocaleString('id-ID');
                            subtotalEl.classList.remove('fade-update');
                        }, 100);

                        updateTotal();
                    }
                })
                .catch(err => {
                    console.error('Error update qty:', err);
                    showAlert('Gagal update jumlah', 'error');
                });
        }
    </script>

    <script>
        function updateTotal() {
            let total = 0;

            document.querySelectorAll('[data-harga]').forEach(card => {
                let harga = parseInt(card.dataset.harga) || 0;
                let qty = parseInt(card.querySelector('.qty').innerText);

                total += harga * qty;
            });

            let fee = 4000;
            let grandTotal = total + fee;

            document.getElementById('subtotal').innerText = 'Rp' + total.toLocaleString('id-ID');
            document.getElementById('grandTotal').innerText = 'Rp' + grandTotal.toLocaleString('id-ID');
            document.getElementById('totalBayar').innerText = 'Rp' + grandTotal.toLocaleString('id-ID');
        }
    </script>

    <script>
        function openNoteModal(id, existingNote = '') {
            document.getElementById('noteItemId').value = id;
            document.getElementById('noteTextarea').value = existingNote;
            let modal = new bootstrap.Modal(document.getElementById('noteModal'));
            modal.show();
        }

        function saveNote() {
            let id = document.getElementById('noteItemId').value;
            let note = document.getElementById('noteTextarea').value;

            let btn = document.getElementById('btnSaveNote');
            btn.disabled = true;
            btn.innerHTML = 'Menyimpan...';

            fetch("{{ route('cart.note') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        row_id: id,
                        note: note
                    })
                })
                .then(res => res.json())
                .then(() => {

                    let item = document.getElementById('item-' + id);
                    if (!item) return;

                    let existingNote = item.querySelector('.note');

                    if (note.trim() === '') {
                        if (existingNote) existingNote.remove();
                    } else {
                        if (existingNote) {
                            existingNote.innerHTML = '<strong>Catatan:</strong> ' + note;
                        } else {
                            let noteDiv = document.createElement('div');
                            noteDiv.className = 'small bg-light rounded p-2 mb-2 note';
                            noteDiv.innerHTML = '<strong>Catatan:</strong> ' + note;

                            let btnNote = item.querySelector('.btn-outline-primary');
                            btnNote.parentNode.insertBefore(noteDiv, btnNote);
                        }
                    }

                    let modalEl = document.getElementById('noteModal');
                    let modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    showAlert('Catatan berhasil disimpan', 'success');

                    btn.disabled = false;
                    btn.innerHTML = 'Tambah';
                })
                .catch(err => {
                    console.error('Error save note:', err);
                    showAlert('Gagal menyimpan catatan', 'error');

                    btn.disabled = false;
                    btn.innerHTML = 'Tambah';
                });
        }
    </script>

    <script>
        function confirmRemove(row_id) {
            Swal.fire({
                title: 'Hapus item?',
                text: 'Pesanan ini akan dihapus dari keranjang',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeItem(row_id);

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Item berhasil dihapus',
                        timer: 1200,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>

    <script>
        function showAlert(message, type = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 2000,
            });
        }
    </script>

@endsection
