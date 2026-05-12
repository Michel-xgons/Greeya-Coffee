<x-filament::page>

    <h2 class="text-xl font-bold mb-2">
        Laporan Tanggal: {{ $tanggal }}
    </h2>

    <div class="mb-4 text-green-600 font-semibold">
        Total Pendapatan: Rp {{ number_format($total, 0, ',', '.') }}
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Customer</th>
                    <th class="px-4 py-2">No Telp</th>
                    <th class="px-4 py-2">Menu</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Waktu</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $index => $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>

                        <td class="px-4 py-2">
                            {{ $item->customer->name ?? '-' }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item->customer->no_telpon ?? '-' }}
                        </td>

                        <td class="px-4 py-2">
                            @foreach ($item->detailPesanans as $detail)
                                <div>
                                    {{ $detail->menu->nama_menu ?? '-' }}
                                    ({{ $detail->jumlah }})
                                </div>
                            @endforeach
                        </td>

                        <td class="px-4 py-2 font-semibold text-green-600">
                            Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item->created_at->format('H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-filament::page>