<!DOCTYPE html>
<html>

<head>
    <title>QR Meja {{ $meja->nomor_meja }}</title>

    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding-top: 40px;
        }

        .card {
            width: 350px;
            margin: auto;
            border: 3px solid black;
            border-radius: 20px;
            padding: 30px;
        }

        .title {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .subtitle {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .url {
            margin-top: 20px;
            font-size: 12px;
            color: gray;
            word-break: break-all;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="title">
            GREEYA COFFEE
        </div>

        <div class="subtitle">
            Scan QR Untuk Melakukan Memesan
        </div>

        <h1>
            Meja {{ $meja->nomor_meja }}
        </h1>

        <div>
            {!! $qr !!}
        </div>

        <div class="url">
            {{ $url }}
        </div>

    </div>

    <br>

    <button onclick="window.print()">
        Cetak QR
    </button>

</body>

</html>
