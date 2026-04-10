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
        </div>
    @endif

    {{-- ================= TAB NAV ================= --}}
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            @foreach ($kategoris as $kategori)
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="nav-{{ $kategori->id }}-tab" data-bs-toggle="tab"
                    href="#nav-{{ $kategori->id }}" role="tab">

                    {{ $kategori->nama_kategori }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- ================= ERROR ================= --}}
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            @foreach ($errors->all() as $error)
                <p class="mb-0">Warning! {{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- ================= TAB CONTENT ================= --}}
    <div class="tab-content mt-3">
        @foreach ($kategoris as $kategori)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="nav-{{ $kategori->id }}" role="tabpanel">

                @if ($kategori->menus->count() > 0)
                    <div class="row">

                        @foreach ($kategori->menus as $item)
                            <div class="col-6 col-md-6 col-lg-4 mb-4">

                                <div class="card product-card h-100 shadow-sm border-0 rounded-4">
                                    <div class="card-body py-3">

                                        <a href="{{ route('detail.menu', $item->id) }}">
                                            <img src="{{ asset('storage/' . $item->gambar) }}"
                                                class="w-100 object-fit-cover rounded-top mb-2" style="height: 180px;">
                                        </a>

                                        <h6 class="fw-semibold mb-1">
                                            {{ $item->nama_menu }}
                                        </h6>

                                        <div class="fw-bold text-dark mb-2">
                                            Rp {{ number_format($item->harga) }}
                                        </div>

                                        {{-- ================= MINUMAN ================= --}}
                                        
                                            <div class="d-flex align-items-center justify-content-between mb-3 mt-3">
                                                <div class="d-flex align-items-center border rounded-pill px-2">
                                                    <button type="button" class="btn btn-sm border-0"
                                                        onclick="changeQty(this, -1)">−</button>

                                                    <input type="number" value="1" name="qty" min="1"
                                                        class="border-0 text-center" style="width:30px">

                                                    <button type="button" class="btn btn-sm border-0"
                                                        onclick="changeQty(this, 1)">+</button>
                                                </div>
                                            </div>

                                            <a href="{{ route('detail.menu', $item->id) }}"
                                                class="btn btn-dark rounded-pill w-100 fw-semibold go-detail">
                                                Tambah
                                            </a>                                                   
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="text-center mt-5">
                        <img src="{{ asset('images/notfound.png') }}" width="80" alt="not found">
                        <h6 class="text-muted mt-3">
                            <b>Tidak ditemukan!</b>
                        </h6>
                    </div>
                @endif

            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // go detail + qty
            document.querySelectorAll('.go-detail').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const card = this.closest('.product-card');
                    if (!card) return;

                    const qtyInput = card.querySelector('input[name="qty"]');
                    const qty = qtyInput ? qtyInput.value : 1;

                    const url = new URL(this.href);
                    url.searchParams.set('qty', qty);

                    window.location.href = url.toString();
                });
            });

            // add to cart AJAX
        });

        function updateCartUI(data) {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) cartCount.innerText = data.total_item;
        }

        function changeQty(btn, change) {
            let input = btn.parentElement.querySelector('input[name="qty"]');
            if (!input) return;

            let val = parseInt(input.value) || 1;
            if (change < 0 && val <= 1) return;

            input.value = val + change;
        }
    </script>

@endsection
