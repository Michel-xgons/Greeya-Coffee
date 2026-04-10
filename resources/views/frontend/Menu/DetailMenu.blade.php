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
                <div class="fw-semibold mb-3">
                    @if ($menu->variants->isNotEmpty())
                        Rp {{ number_format($menu->variants->min('harga'), 0, ',', '.') }}
                    @else
                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                    @endif
                </div>
                <div class="fw-semibold mb-2">
                    {{ $menu->deskripsi }}
                </div>

                <hr>

                <form action="{{ route('cart.add') }}" method="POST" class="cart-form">
                    @csrf




                    <!-- variant -->
                    @if ($menu->variants->isNotEmpty())

                        <div class="fw-semibold mb-2">Pilih Varian</div>

                        @foreach ($menu->variants as $index => $variant)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="variant_id" value="{{ $variant->id }}"
                                    {{ $index == 0 ? 'checked' : '' }} required>

                                <label class="form-check-label">
                                    {{ $variant->nama_variant }} -
                                    Rp {{ number_format($variant->harga, 0, ',', '.') }}
                                </label>
                            </div>
                        @endforeach
                    @else
                        {{-- ✅ UNTUK MAKANAN --}}
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                    @endif

                    <!-- qty -->
                    <input type="hidden" name="qty" value="{{ $qty }}">

                    <!-- note -->
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

                const variantInput = document.querySelectorAll('input[name="variant_id"]');

                if (variantInput.length > 0) {
                    const selected = document.querySelector('input[name="variant_id"]:checked');
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

                            form.reset();

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
