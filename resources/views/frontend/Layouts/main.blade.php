<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'greeya coffee')</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
</head>

<body>

    {{-- Header --}}
    <header class="position-relative">
        <div class="bg-secondary py-3 text-center position-relative">
            <img src="{{ asset('images/Logo.png') }}" style="width:200px; height:100px; object-fit:contain;">
            <div class="position-absolute top-0 end-0 d-flex gap-2 m-2">
                <button class="btn btn-light rounded-circle p-2 shadow-sm" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-light rounded-circle p-2 shadow-sm" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Tombol Keranjang -->
                <div class="position-relative">
                    <button class="btn btn-light rounded-circle p-2 shadow-sm" id="cartBtn" aria-label="Keranjang">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                    <span id="cartCount"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </div>

            </div>
        </div>
    </header>

    <!-- Offcanvas Keranjang -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="cartOffcanvasLabel">Keranjang Belanja</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div id="cartItems">
                <p class="text-muted text-center">Keranjang masih kosong</p>
            </div>
        </div>
        <div class="offcanvas-footer border-top p-3">
            <button class="btn btn-primary w-100">Checkout</button>
        </div>
    </div>
    <!-- Floating Cart Bar -->
    <div id="cartBar" class="d-none position-fixed bottom-0 start-0 end-0 bg-secondary text-white py-3 px-4 shadow-lg"
        style="z-index: 1050; border-radius: 1rem 1rem 0 0;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-shopping-basket fs-4"></i>
                <div>
                    <div class="fw-bold">Total</div>
                    <div id="cartTotal" class="fs-5 fw-semibold">Rp0</div>
                </div>
            </div>
            <button id="checkoutBtn" class="btn btn-light fw-bold rounded-pill">
                CHECK OUT (<span id="checkoutCount">0</span>)
            </button>
        </div>
    </div>


    {{-- Konten utama tiap halaman --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- Footer --}}


    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.__akat_cart_initialized) return;
            window.__akat_cart_initialized = true;

            const cartBar = document.getElementById('cartBar');
            const cartTotalEl = document.getElementById('cartTotal');
            const checkoutCountEl = document.getElementById('checkoutCount');
            const cartCountEl = document.getElementById('cartCount');

            function computeTotalsFromDOM() {
                const cards = document.querySelectorAll('.card-body[data-price]');
                let totalItems = 0;
                let totalHarga = 0;

                cards.forEach(card => {
                    const qty = parseInt(card.dataset.qty || '0', 10);
                    const price = parseInt(card.dataset.price || '0', 10);
                    if (qty > 0) {
                        totalItems += qty;
                        totalHarga += qty * price;
                    }
                });

                return {
                    totalItems,
                    totalHarga
                };
            }

            function updateCartDisplay() {
                const totals = computeTotalsFromDOM();
                cartCountEl.textContent = totals.totalItems || 0;
                cartTotalEl.textContent = `Rp${(totals.totalHarga || 0).toLocaleString('id-ID')}`;
                checkoutCountEl.textContent = totals.totalItems || 0;
                cartBar.classList.toggle('d-none', totals.totalItems === 0);
            }

            // Event add / plus / minus
            document.addEventListener('click', (e) => {
                const addBtn = e.target.closest('.add-to-cart');
                const plusBtn = e.target.closest('.plus-btn');
                const minusBtn = e.target.closest('.minus-btn');

                if (addBtn) {
                    const card = addBtn.closest('.card-body');
                    const control = card.querySelector('.quantity-control');
                    const qtySpan = control.querySelector('.quantity');
                    control.classList.remove('d-none');
                    addBtn.classList.add('d-none');
                    card.dataset.qty = '1';
                    qtySpan.textContent = '1';
                    updateCartDisplay();
                }

                if (plusBtn) {
                    const card = plusBtn.closest('.card-body');
                    const qtySpan = card.querySelector('.quantity');
                    let qty = parseInt(card.dataset.qty || '0', 10);
                    qty++;
                    card.dataset.qty = qty;
                    qtySpan.textContent = qty;
                    updateCartDisplay();
                }

                if (minusBtn) {
                    const card = minusBtn.closest('.card-body');
                    const qtySpan = card.querySelector('.quantity');
                    let qty = parseInt(card.dataset.qty || '0', 10);
                    qty--;
                    if (qty <= 0) {
                        card.dataset.qty = '0';
                        qtySpan.textContent = '0';
                        card.querySelector('.quantity-control').classList.add('d-none');
                        card.querySelector('.add-to-cart').classList.remove('d-none');
                    } else {
                        card.dataset.qty = qty;
                        qtySpan.textContent = qty;
                    }
                    updateCartDisplay();
                }
            });

            // Tombol checkout
            document.getElementById('checkoutBtn')?.addEventListener('click', (e) => {
                e.stopPropagation();

                const totals = computeTotalsFromDOM();
                const items = [];

                document.querySelectorAll('.card-body[data-price]').forEach(card => {
                    const qty = parseInt(card.dataset.qty || '0', 10);
                    if (qty > 0) {
                        items.push({
                            name: card.dataset.name,
                            price: parseInt(card.dataset.price, 10),
                            qty,
                            note: card.dataset.note || '',
                            variant: card.dataset.variant || ''
                        });

                    }
                });

                localStorage.setItem('greeya_cart', JSON.stringify({
                    items,
                    totalHarga: totals.totalHarga,
                    totalItems: totals.totalItems
                }));

                window.location.href = '/checkout';
            });

            // Restore dari localStorage
            const savedData = JSON.parse(localStorage.getItem('greeya_cart') || '{}');
            if (savedData.items && savedData.items.length > 0) {
                savedData.items.forEach(item => {
                    const card = Array.from(document.querySelectorAll('.card-body[data-name]'))
                        .find(c => c.dataset.name === item.name);

                    if (card) {
                        card.dataset.qty = item.qty;
                        const control = card.querySelector('.quantity-control');
                        const addBtn = card.querySelector('.add-to-cart');
                        const qtySpan = card.querySelector('.quantity');
                        control.classList.remove('d-none');
                        addBtn.classList.add('d-none');
                        qtySpan.textContent = item.qty;
                    }
                });
            }

            updateCartDisplay();
        });
    </script>


    @yield('scripts')
</body>

</html>
