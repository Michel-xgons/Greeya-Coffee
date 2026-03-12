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

                    <button class="btn btn-light rounded-circle p-2 shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#checkoutModal">

                        <i class="fas fa-shopping-cart"></i>

                    </button>

                    <span id="cartCount"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                        @php
                            $cart = session('cart', []);
                            $total_item = collect($cart)->sum('qty');
                        @endphp

                        {{ $total_item }}

                    </span>

                </div>

                <!-- Modal Checkout -->
                <div class="modal fade" id="checkoutModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Ringkasan Pesanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>


                            <div class="modal-body">

                                <div id="cartItems">

                                    @php
                                        $cart = session('cart', []);
                                        $total = 0;
                                    @endphp

                                    @forelse($cart as $item)
                                        @php
                                            $subtotal = $item['harga'] * $item['qty'];
                                            $total += $subtotal;
                                        @endphp

                                        <div
                                            class="d-flex justify-content-between align-items-center border-bottom py-2">

                                            <div class="text-start">

                                                <strong>
                                                    {{ $item['nama'] }}
                                                    @if (!empty($item['varian']))
                                                        ({{ $item['varian'] }})
                                                    @endif
                                                </strong>
                                                
                                                <br>

                                                <small class="text-muted">
                                                    {{ $item['qty'] }} x Rp
                                                    {{ number_format($item['harga'], 0, ',', '.') }}
                                                </small>

                                            </div>

                                            <div class="fw-bold">

                                                Rp {{ number_format($subtotal, 0, ',', '.') }}

                                            </div>

                                        </div>

                                    @empty

                                        <div class="text-center text-muted py-3">
                                            Keranjang masih kosong
                                        </div>
                                    @endforelse

                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">

                                    <strong>Total</strong>

                                    <strong id="modalTotal" class="text-success">

                                        Rp {{ number_format($total, 0, ',', '.') }}

                                    </strong>

                                </div>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                                <a href="{{ route('checkout') }}" class="btn btn-primary">
                                    Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Konten utama tiap halaman --}}
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!--Validasi-->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999">
        <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    Menu berhasil ditambahkan
                </div>

                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

</body>

</html>
