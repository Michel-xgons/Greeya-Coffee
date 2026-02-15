@extends('frontend.layouts.main')
@section('title', 'Checkout')

@section('content')

    <div class="container my-4 pb-5">
        <h4 class="mb-3 text-center">Pesanan Kamu</h4>
        <div class="row justify-content-center">
            @foreach ($cart as $item)
                <div class="col-md-8">
                    <div class="card mb-3 shadow-sm">
                        <div class="row g-0 align-items-center">

                            <div class="col-4">
                                <img src="..." class="img-fluid rounded-start h-100 object-fit-cover" alt="...">
                            </div>

                            <div class="col-8">
                                <div class="card-body py-3">

                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-1">{{ $item['name'] }}</h5>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-sm btn-outline-dark"
                                                onclick="updateQty('{{ $item['id'] }}', -1)">−</button>
                                            <span class="fw-bold">{{ $item['qty'] }}</span>
                                            <button class="btn btn-sm btn-outline-dark"
                                                onclick="updateQty('{{ $item['id'] }}', 1)">+</button>
                                        </div>
                                    </div>

                                    <div class="small text-muted">
                                        Harga: Rp{{ number_format($item['price'], 0, ',', '.') }}
                                    </div>

                                    <div class="fw-semibold mb-2">
                                        Subtotal: Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                    </div>

                                    @if (!empty($item['note']))
                                        <div class="small bg-light rounded p-2 mb-2">
                                            <strong>Catatan:</strong> {{ $item['note'] }}
                                        </div>
                                    @endif
                                    <button class="btn btn-outline-primary btn-sm"
                                        onclick="openNoteModal('{{ $item['id'] }}', '{{ $item['note'] ?? '' }}')">
                                        Tambah Catatan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('Branda') }}">
                <button type="button" class="btn btn-secondary">Tambah Pesanan</button>
            </a>
        </div>

        <div class="card mb-5 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Rincian Pembayaran</h5>
                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Biaya lainnya</span>
                    <span id="fee">Rp{{ number_format(170, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span id="grandTotal">{{ 'Rp' . number_format($total + 170, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FIXED BOTTOM -->
    <div class="fixed-bottom bg-white py-3 px-4 border-top shadow-lg">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold">Total Pembayaran</div>
                <div id="totalBayar" class="fs-5 text-danger fw-semibold">
                    {{ 'Rp' . number_format($total + 170, 0, ',', '.') }}
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

    <script>
        function updateQty(id, change) {
            fetch("{{ route('cart.update') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: id,
                        change: change
                    })
                })
                .then(res => res.json())
                .then(() => {
                    location.reload();
                });
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

            fetch("{{ route('cart.note') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: id,
                        note: note
                    })
                })
                .then(res => res.json())
                .then(() => {
                    location.reload();
                });
        }
    </script>

@endsection
