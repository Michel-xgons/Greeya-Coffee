<div style="text-align: center; padding: 20px;">

    <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">
        Meja {{ $meja->nomor_meja }}
    </h2>

    <div style="margin-bottom: 20px;">
        {!! $qr !!}
    </div>

    <a 
        href="{{ route('meja.print', $meja->id) }}"
        target="_blank"
        style="
            display: inline-block;
            padding: 10px 20px;
            background-color: #16a34a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
        "
    >
        Cetak QR
    </a>

    <p style="
        font-size: 12px;
        color: gray;
        margin-top: 10px;
        word-break: break-all;
    ">
        {{ $url }}
    </p>

</div>