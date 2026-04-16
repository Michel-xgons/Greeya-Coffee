@extends('frontend.layouts.main')
@section('title', 'Detail Menu')

@section('content')

    <div class="container-fluid p-0">

        <!-- GAMBAR PRODUK -->
        <div class="card bg-secondary">
            <img src="{{ asset('storage/' . $menu->gambar) }}" class="img-fluid w-50 mx-auto d-block" alt="...">
        </div>

        <div class="card rounded-top-4 mt-n3">
            <div class="card-body">

                <h5 class="fw-bold mb-1">{{ $menu->nama_menu }}</h5>

                <!-- HARGA -->
                <div class="fw-semibold mb-3">
                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                </div>

                <div class="fw-semibold mb-2">
                    {{ $menu->deskripsi }}
                </div>

                <hr>

                <form action="{{ route('cart.add') }}" method="POST" class="cart-form">
                    @csrf

                    <!-- VARIANT -->
                    @if ($menu->kategori->nama_kategori === 'Minuman')
                        <div class="fw-semibold mb-2">Pilih Varian</div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="variant" value="hot" >
                            <label class="form-check-label">Hot</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="variant" value="ice" checked>
                            <label class="form-check-label">Ice</label>
                        </div>
                    @endif

                    <!-- MENU ID -->
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                    <!-- QTY -->
                    <input type="hidden" name="qty" value="{{ $qty }}">

                    <!-- NOTE -->
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        Tambah Pesanan
                    </button>

                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.querySelector('.cart-form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const btn = form.querySelector('button');
                btn.disabled = true;

                // 🔥 VALIDASI VARIANT
                const variantInputs = document.querySelectorAll('input[name="variant"]');

                if (variantInputs.length > 0) {
                    const selected = document.querySelector('input[name="variant"]:checked');

                    if (!selected) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Pilih varian dulu!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        btn.disabled = false;
                        return;
                    }
                }

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) throw data;
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

                            // reset note saja
                            form.querySelector('textarea[name="note"]').value = '';

                            const cartCount = document.getElementById('cartCount');
                            const cartItems = document.getElementById('cartItems');
                            const modalTotal = document.getElementById('modalTotal');

                            if (cartCount) cartCount.innerText = data.total_item;
                            if (cartItems) cartItems.innerHTML = data.html;
                            if (modalTotal) {
                                modalTotal.innerText = "Rp " + data.total.toLocaleString('id-ID');
                            }
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.message || 'Terjadi kesalahan',
                        });
                    })
                    .finally(() => {
                        btn.disabled = false;
                    });

            });

        });
    </script>

@endsection
