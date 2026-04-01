@extends('frontend.layouts.main')
@section('title', 'greeya coffee')
@section('content')

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1 fw-bold text-capitalize">Greeya Coffee</h6>
                <small class="text-muted d-flex align-items-center gap-1">
                    <i class="fas fa-clock"></i>
                    Buka hari ini, 15:00 – 02:00
                </small>
            </div>
            <div class="text-muted">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>

    @if (session('nomor_meja'))
        <div class="bg-warning bg-opacity-10 text-center fw-semibold rounded-4 py-2 my-3 mx-auto border border-warning-subtle"
            style="max-width: 300px;">
            <i class="fas fa-chair me-1 text-warning"></i>
            Nomor Meja:
            <span class="fw-bold">{{ session('nomor_meja') }}</span>
            {{-- <span class="fw-bold">Menyesuaikan No Meja</span> --}}
        </div>
    @endif

    <nav>
        <div class="nav nav-tabs col-sm-12" id="nav-tab" role="tablist">

            @foreach ($kategoris as $kategori)
                <a class="nav-link fs-6 {{ $loop->first ? 'active' : '' }}" id="nav-{{ $kategori->id }}-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-{{ $kategori->id }}" role="tab"
                    aria-controls="nav-{{ $kategori->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">

                    {{ $kategori->nama_kategori }}

                </a>
            @endforeach


        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">Warning! {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @foreach ($kategoris as $kategori)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="nav-{{ $kategori->id }}" role="tabpanel"
                aria-labelledby="nav-{{ $kategori->id }}-tab">

                @if ($kategori->menus->count() > 0)
                    <div class="row mt-4">

                        @foreach ($kategori->menus as $item)
                            <div class="col-6 col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm border-0 rounded-4">
                                    <div class="card-body product-item py-3">

                                        <figure>
                                            <a href="{{ route('detail.menu', $item->id) }}">

                                                <img src="{{ asset('storage/' . $item->gambar) }}"
                                                    class="w-100 object-fit-cover rounded-top" style="height: 180px;">
                                            </a>
                                        </figure>

                                        <h6 class="fw-semibold mb-1">{{ $item->nama_menu }}</h6>

                                        <div class="fw-bold text-dark mb-2">
                                            {{ 'Rp.' . number_format($item->harga) }}
                                        </div>

                                        @if (optional($kategori)->nama_kategori == 'Minuman')
                                            <div class="d-flex align-items-center justify-content-between mb-3 mt-3">

                                                <div class="d-flex align-items-center border rounded-pill px-2">

                                                    <button type="button" class="btn btn-sm border-0"
                                                        onclick="changeQty(this, -1)">
                                                        −
                                                    </button>

                                                    <input type="number" value="1" name="qty" min="1"
                                                        class="border-0 text-center" style="width:30px">

                                                    <button type="button" class="btn btn-sm border-0"
                                                        onclick="changeQty(this, 1)">
                                                        +
                                                    </button>

                                                </div>

                                            </div>

                                            <a href="{{ route('detail.menu', $item->id) }}"
                                                class="col-12 btn btn-dark rounded-pill w-100 fw-semibold go-detail">
                                                Tambah
                                            </a>
                                        @else
                                            <form action="{{ route('cart.add') }}" method="POST" class="cart-form">
                                                @csrf

                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <input type="hidden" name="nama" value="{{ $item->nama_menu }}">
                                                <input type="hidden" name="harga" value="{{ $item->harga }}">

                                                <div>
                                                    <div
                                                        class="d-flex align-items-center justify-content-between mb-3 mt-3">

                                                        <div class="d-flex align-items-center border rounded-pill px-2">

                                                            <button type="button" class="btn btn-sm border-0"
                                                                onclick="changeQty(this, -1)">
                                                                −
                                                            </button>

                                                            <input type="number" value="1" name="qty"
                                                                min="1" class="border-0 text-center"
                                                                style="width:30px">

                                                            <button type="button" class="btn btn-sm border-0"
                                                                onclick="changeQty(this, 1)">
                                                                +
                                                            </button>

                                                        </div>

                                                        {{-- <small>Max:10</small> --}}
                                                    </div>
                                                    <button type="submit"
                                                        class="col-12 btn btn-dark rounded-pill w-100 fw-semibold">
                                                        Tambah
                                                    </button>

                                                </div>
                                            </form>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="col mb-5 mt-5">
                        <div class="text-center mt-5">
                            <img src="{{ asset('images/notfound.png') }}" width="10%" alt="not found">
                            <h6 class="text-muted mt-3">
                                <b>Tidak ditemukan!</b>
                            </h6>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 not loaded!');
                return;
            }

            document.querySelectorAll('.go-detail').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const card = this.closest('.product-item');
                    const qtyInput = card.querySelector('input[name="qty"]');
                    const qty = qtyInput ? qtyInput.value : 1;
                    const url = new URL(this.href);
                    url.searchParams.set('qty', qty);
                    window.location.href = url.toString();
                });
            });

            document.querySelectorAll('.cart-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const btn = form.querySelector('button[type="submit"]');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';

                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData, // FormData sudah include CSRF
                            credentials: 'same-origin'
                        })
                        .then(async res => {
                            const data = await res.json();

                            if (!res.ok) {
                                const errorMsg = data.message || data.error ||
                                    'Terjadi kesalahan server';
                                throw new Error(errorMsg);
                            }

                            return data;
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Menu ditambahkan ke keranjang',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Update cart UI
                                updateCartUI(data);
                            }
                        })
                        .catch(err => {
                            console.error('Cart error:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: err.message ||
                                    'Terjadi kesalahan saat menambahkan ke keranjang'
                            });
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.innerHTML = 'Tambah';
                        });
                });
            });
        });

        function updateCartUI(data) {
            const cartCount = document.getElementById('cartCount');
            const cartItems = document.getElementById('cartItems');
            const modalTotal = document.getElementById('modalTotal');

            if (cartCount) cartCount.innerText = data.total_item;
            if (cartItems) cartItems.innerHTML = data.html;
            if (modalTotal) modalTotal.innerText = "Rp " + data.total.toLocaleString('id-ID');
        }
    </script>

    <script>
        function changeQty(btn, change) {
            let input = btn.parentElement.querySelector('input[name="qty"]');
            if (!input) return;

            let val = parseInt(input.value) || 1;

            if (change < 0 && val <= 1) return; // Min 1
            if (val + change < 1) return;

            input.value = val + change;
        }
    </script>

@endsection
