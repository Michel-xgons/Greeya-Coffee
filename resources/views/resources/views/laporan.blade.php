<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>

    <link rel="stylesheet" href="{{ public_path('css/laporan.css') }}">
</head>

<body>

    <div class="header">
        <h2>Laporan Penjualan</h2>

        <div class="info">
            Periode:
            {{ ucfirst($filter) }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>No Telp</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Waktu</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($data as $item)
                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->customer->name ?? '-' }}</td>

                    <td>{{ $item->customer->no_telpon ?? '-' }}</td>

                    <td>
                        @foreach ($item->detailPesanans as $detail)
                            {{ $detail->menu->nama_menu ?? '-' }}<br>
                        @endforeach
                    </td>

                    <td>
                        @foreach ($item->detailPesanans as $detail)
                            {{ $detail->jumlah }}<br>
                        @endforeach
                    </td>

                    <td>
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ $item->created_at->format('d-m-Y H:i') }}
                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>

    <div class="total-box">
        Total Pendapatan:
        Rp {{ number_format($total, 0, ',', '.') }}
    </div>

</body>

</html>
