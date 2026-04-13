<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'greeya coffee')</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

@php
    $cart = session('cart', []);
    $total_item = collect($cart)->sum('qty');
    $total = collect($cart)->sum(function ($item) {
        return $item['harga'] * $item['qty'];
    });
@endphp

<body>


    {{-- Header --}}
    <header class="position-relative">
        <div class="position-relative py-2 px-3 d-flex align-items-center justify-content-end shadow-sm"
            style="background:#3E2723;">

            <!-- Logo -->
            <div class="position-absolute top-50 start-50 translate-middle">
                <img src="{{ asset('images/Logo.png') }}" class="img-fluid"
                    style="max-height:70px; object-fit:contain;">
            </div>

            <!-- Right Menu -->
            <div class="d-flex align-items-center gap-2 position-relative">

                <input id="searchBox" class="form-control position-absolute top-100 end-0 mt-2 shadow"
                    style="width:250px; display:none; z-index:999;" placeholder="Cari menu..." autocomplete="off">

                <button id="btnSearch" class="btn btn-light rounded-circle p-2 shadow-sm">
                    <i class="fas fa-search fs-5"></i>
                </button>

                <button class="btn btn-light rounded-circle p-2 shadow-sm" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasMenu">
                    <i class="fas fa-bars fs-5"></i>
                </button>

                <!-- Cart -->
                <div class="position-relative">
                    <button class="btn btn-light rounded-circle p-2 shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#checkoutModal">
                        <i class="fas fa-shopping-cart fs-5"></i>
                    </button>

                    <span id="cartCount"
                        class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-danger">
                        {{ $total_item }}
                    </span>
                </div>

            </div>
        </div>


    </header>

    {{-- Konten utama tiap halaman --}}
    <main class="container-fluid px-3 py-4">
        @yield('content')
    </main>

    <!-- Modal Checkout -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">🛒 Ringkasan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Cart Items -->
                    <div id="cartItems">
                        <div class="text-center text-muted py-4">
                            <div class="spinner-border spinner-border-sm mb-2"></div>
                            <div>Memuat pesanan...</div>
                        </div>
                    </div>

                    <hr>

                    <!-- Total -->
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Total</span>

                        <span id="modalTotal" class="fw-bold text-success fs-5">
                            Rp 0
                        </span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>

                    <a href="{{ route('checkout') }}" class="btn btn-primary fw-semibold">
                        Checkout
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" id="offcanvasMenu">
        <div class="offcanvas-header">
            <h5>Menu</h5>
            <button class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{ route('Branda') }}" class="text-decoration-none text-dark d-block">
                        Halaman Beranda
                    </a>
                </li>

                <li class="list-group-item">
                    <a href="{{ route('riwayat.pesanan') }}" class="text-decoration-none text-dark d-block">
                        Halaman Riwayat pesanan
                    </a>
                </li>

            </ul>
        </div>
    </div>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const btnSearch = document.getElementById('btnSearch');
            const searchBox = document.getElementById('searchBox');
            const checkoutModal = document.getElementById('checkoutModal');
            const cartBtn = document.querySelector('[data-bs-target="#checkoutModal"]');

            let isOpen = false;
            let timeout = null;

            // 🔍 TOGGLE SEARCH
            if (btnSearch && searchBox) {

                btnSearch.addEventListener('click', function() {
                    isOpen = !isOpen;

                    if (isOpen) {
                        searchBox.style.display = 'block';
                        searchBox.focus();
                    } else {
                        searchBox.style.display = 'none';
                        searchBox.value = '';
                        resetItems();
                    }
                });

                // 🔎 SEARCH (FETCH)
                searchBox.addEventListener('input', function() {
                    let keyword = this.value;

                    clearTimeout(timeout);

                    timeout = setTimeout(() => {

                        if (keyword.length < 1) {
                            resetItems();
                            return;
                        }

                        fetch(`/search-menu?keyword=${encodeURIComponent(keyword)}`)
                            .then(res => res.json())
                            .then(data => {

                                let container = document.getElementById('menuContainer');
                                if (!container) return;

                                container.innerHTML = '';

                                if (data.length === 0) {
                                    container.innerHTML =
                                        `<p class="text-center">Menu tidak ditemukan</p>`;
                                    return;
                                }

                                data.forEach(menu => {
                                    container.innerHTML += `
                                <div class="col-md-3 product-item">
                                    <div class="card p-2">
                                        <h6>${menu.nama}</h6>
                                        <p>Rp ${menu.harga}</p>
                                    </div>
                                </div>
                            `;
                                });

                            })
                            .catch(() => {
                                console.log('Error fetch search');
                            });

                    }, 300);
                });
            }

            // 🔄 RESET
            function resetItems() {
                fetch(`/search-menu?keyword=`)
                    .then(res => res.json())
                    .then(data => {

                        let container = document.getElementById('menuContainer');
                        if (!container) return;

                        container.innerHTML = '';

                        data.forEach(menu => {
                            container.innerHTML += `
                        <div class="col-md-3 product-item">
                            <div class="card p-2">
                                <h6>${menu.nama}</h6>
                                <p>Rp ${menu.harga}</p>
                            </div>
                        </div>
                    `;
                        });

                    });
            }

            // ✅ FIX MODAL FOCUS
            if (checkoutModal) {
                checkoutModal.addEventListener('hidden.bs.modal', function() {
                    if (cartBtn) {
                        cartBtn.focus();
                    }
                });
            }

            // ✅ AUTO CLOSE SEARCH
            document.addEventListener('click', function(e) {

                // ❗ JANGAN ganggu form submit
                if (e.target.closest('form')) return;

                if (
                    btnSearch &&
                    searchBox &&
                    !btnSearch.contains(e.target) &&
                    !searchBox.contains(e.target)
                ) {
                    searchBox.style.display = 'none';
                    isOpen = false;
                }
            });

            // ✅ LOAD CART SAAT MODAL DIBUKA
            if (checkoutModal) {
                checkoutModal.addEventListener('shown.bs.modal', function() {

                    fetch('/cart', {
                            credentials: 'same-origin'
                        })
                        .then(res => res.json())
                        .then(data => {

                            const cartItems = document.getElementById('cartItems');
                            const modalTotal = document.getElementById('modalTotal');

                            if (cartItems) {
                                cartItems.innerHTML = data.html;
                            }

                            if (modalTotal) {
                                modalTotal.innerText =
                                    "Rp " + Number(data.total).toLocaleString('id-ID');
                            }

                        });

                });
            }
        });
    </script>

    <script>
        document.querySelector('#checkoutModal')?.addEventListener('click', function(e) {

            // ➕➖ QTY
            if (e.target.classList.contains('qty-btn')) {

                fetch("{{ route('cart.update') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            row_id: e.target.dataset.id,
                            action: e.target.dataset.action
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('cartItems').innerHTML = data.html;
                        document.getElementById('modalTotal').innerText =
                            "Rp " + data.total.toLocaleString('id-ID');
                        document.getElementById('cartCount').innerText = data.total_item;
                    });
            }

            //  DELETE
            const deleteBtn = e.target.closest('.delete-btn');

            if (deleteBtn) {

                Swal.fire({
                    title: 'Hapus item?',
                    text: 'Item akan dihapus dari keranjang',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {

                    if (result.isConfirmed) {

                        fetch("{{ route('cart.remove') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    row_id: deleteBtn.dataset.id
                                })
                            })
                            .then(res => res.json())
                            .then(data => {

                                document.getElementById('cartItems').innerHTML = data.html;
                                document.getElementById('modalTotal').innerText =
                                    "Rp " + data.total.toLocaleString('id-ID');
                                document.getElementById('cartCount').innerText = data.total_item;

                                // 🔥 Notifikasi sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dihapus!',
                                    text: 'Item berhasil dihapus',
                                    timer: 1200,
                                    showConfirmButton: false
                                });

                            });

                    }

                });

            }

        });
    </script>

</body>

</html>
