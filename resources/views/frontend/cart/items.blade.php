@php $total = 0; @endphp

@if (empty($cart))
    <div class="text-center py-4">
        <p class="text-muted mb-2">Belum ada pesanan</p>
    </div>
@else
    @foreach ($cart as $item)
        @php
            $subtotal = $item['harga'] * $item['qty'];
            $total += $subtotal;
        @endphp

        <div class="border-bottom py-2">

            <div class="d-flex justify-content-between">

                <div>
                    <div class="fw-semibold">
                        {{ $item['nama'] }}
                    </div>

                    @if (!empty($item['variant']))
                        <div class="small text-muted">
                            Varian: {{ strtoupper($item['variant']) }}
                        </div>
                    @endif

                    <div class="small text-muted">
                        Rp {{ number_format($item['harga'], 0, ',', '.') }}
                    </div>
                </div>

                <div class="text-end">

                    <!-- SUBTOTAL -->
                    <div class="fw-bold mb-1">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </div>

                    <!-- QTY CONTROL -->
                    <div class="d-flex align-items-center gap-1 justify-content-end">

                        <button class="btn btn-sm btn-outline-secondary qty-btn" data-id="{{ $item['row_id'] }}"
                            data-action="minus">-</button>

                        <span>{{ $item['qty'] }}</span>

                        <button class="btn btn-sm btn-outline-secondary qty-btn" data-id="{{ $item['row_id'] }}"
                            data-action="plus">+</button>

                        <!-- DELETE -->
                        <button class="btn btn-sm btn-outline-danger ms-2 delete-btn" data-id="{{ $item['row_id'] }}">
                            <i class="bi bi-trash"></i>
                        </button>

                    </div>

                </div>

            </div>

        </div>
    @endforeach

@endif
