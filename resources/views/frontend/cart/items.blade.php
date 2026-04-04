@php $total = 0; @endphp

@foreach ($cart as $item)
    @php
        $subtotal = $item['harga'] * $item['qty'];
        $total += $subtotal;
    @endphp

    <div class="d-flex justify-content-between align-items-center border-bottom py-2">

    <div class="text-start">
        <strong>
            {{ $item['nama'] }}

            @if (!empty($item['varian']))
                ({{ $item['varian'] }})
            @endif
        </strong>

        <br>

        <small class="text-muted">
            Qty: {{ $item['qty'] }} <br>
            {{ $item['qty'] }} x Rp {{ number_format($item['harga'], 0, ',', '.') }}
        </small>
    </div>

    <div class="fw-bold">
        Rp {{ number_format($subtotal, 0, ',', '.') }}
    </div>

</div>
@endforeach
