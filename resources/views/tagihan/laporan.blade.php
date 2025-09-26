<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-xl">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Laporan Keuangan Tagihan</h2>

        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <form method="GET"
                class="flex items-center gap-2 bg-gray-50 border border-gray-200 px-4 py-2 rounded-md shadow-sm">
                <label for="bulan" class="text-sm font-medium text-gray-700">Bulan:</label>
                <input type="number" name="bulan" value="{{ $bulan }}" min="1" max="12"
                    class="w-20 border border-gray-300 rounded-md text-sm p-1 focus:ring-indigo-500 focus:border-indigo-500" />

                <label for="tahun" class="text-sm font-medium text-gray-700">Tahun:</label>
                <input type="number" name="tahun" value="{{ $tahun }}"
                    class="w-24 border border-gray-300 rounded-md text-sm p-1 focus:ring-indigo-500 focus:border-indigo-500" />

                <button type="submit"
                    class="ml-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Tampilkan
                </button>
            </form>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg mb-6">
            <h3 class="text-xl font-semibold my-3 px-3">List Warga yang Sudah Bayar Bulan Ini
                ({{ $bulan }}/{{ $tahun }})</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Warga</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal Bayar</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tagihansSudahBayar as $tagihan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $tagihan->user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                @if (!empty($tagihan->settlement_time))
                                    {{ \Carbon\Carbon::parse($tagihan->settlement_time)->format('d-m-Y H:i') }}
                                @else
                                    <span class="text-red-500">Belum dibayar</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-800">
                                Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="my-4 px-3">
                <p class="text-lg font-bold text-gray-800">
                    Total Bulan Ini: Rp {{ number_format($totalBulanIni, 0, ',', '.') }}
                </p>
            </div>


            <div class="my-4 px-3">
                {{ $tagihansSudahBayar->appends(['bulan_lain_page' => request('bulan_lain_page')])->links() }}
            </div>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <h3 class="text-xl font-semibold my-3 px-3">List Warga yang Sudah Bayar di Bulan Lain</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Warga</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Bulan/Tahun</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tagihansBulanLain as $tagihan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $tagihan->user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('m/Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="my-4 px-3">
                <p class="text-lg font-bold text-gray-800">
                    Total Bulan Lain: Rp {{ number_format($totalBulanLain, 0, ',', '.') }}
                </p>
            </div>


            <div class="my-4 px-3">
                {{ $tagihansBulanLain->appends(['sudah_bayar_page' => request('sudah_bayar_page')])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
