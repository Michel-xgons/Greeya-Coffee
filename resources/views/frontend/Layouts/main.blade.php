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
                    <button class="btn btn-light rounded-circle p-2 shadow-sm" id="btnCheckout" data-bs-toggle="modal"
                        data-bs-target="#checkoutModal">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                    <span id="cartCount"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        @php
                            $cart = session('cart', []);

                            $total_item = 0;
                            foreach ($cart as $item) {
                                $total_item += $item['qty'];
                            }

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

                            <div class="modal-body text-center">
                                @php
                                    $cart = session('cart', []);

                                    $total = 0;
                                    foreach ($cart as $item) {
                                        $total += $item['harga'] * $item['qty'];
                                    }
                                @endphp

                                <h6>Total yang harus dibayar:</h6>

                                <h3 class="fw-bold text-success my-3" id="modalTotal">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </h3>

                                <p class="text-muted">
                                    Pastikan pesanan Anda sudah benar sebelum checkout
                                </p>

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

    {{-- Footer --}}

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
