<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Iuran Bulanan</h2>

        <!-- Form Pilih Bulan dan Tahun -->
        <form action="{{ route('tagihan.index') }}" method="GET" class="flex flex-wrap gap-4 items-center mb-6">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label for="bulan" class="font-semibold text-gray-600">Bulan:</label>
                <select name="bulan" class="border py-2 rounded focus:ring focus:ring-blue-200 w-full sm:w-auto">
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}" {{ $bulan == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label for="tahun" class="font-semibold text-gray-600">Tahun:</label>
                <select name="tahun" class="border py-2 rounded focus:ring focus:ring-blue-200 w-full sm:w-auto">
                    @foreach (range(now()->year - 5, now()->year + 4) as $year)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}
                        </option>
                    @endforeach

                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Tampilkan
            </button>
        </form>

        <!-- Notifikasi Tidak Ada Tagihan -->
        @if ($tagihansBelumBayar->isEmpty() && $tagihansSudahBayar->isEmpty())
            <div class="flex items-center justify-center bg-yellow-100 text-yellow-700 p-4 rounded-lg">
                <p class="font-semibold">Belum ada tagihan untuk bulan ini.</p>
            </div>
        @else
            <!-- Tabel Tagihan Belum Dibayar -->
            @if (!$tagihansBelumBayar->isEmpty())
                <div class="mt-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Tagihan Belum Dibayar</h3>
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-[600px] w-full bg-white shadow-md rounded-lg text-sm">
                            <thead class="bg-blue-50 text-blue-700">
                                <tr>
                                    <th class="px-6 py-3 whitespace-nowrap">Iuran</th>
                                    <th class="px-6 py-3 whitespace-nowrap">Jatuh Tempo</th>
                                    <th class="px-6 py-3 whitespace-nowrap">Status</th>
                                    <th class="px-6 py-3 whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($tagihansBelumBayar as $tagihan)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium">{{ $tagihan->iuran->nama ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">
                                                <i class="fas fa-times-circle mr-1"></i> Belum Dibayar
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('tagihan.bayar', $tagihan) }}" method="POST"
                                                class="flex gap-2 justify-center">
                                                @csrf
                                                <input type="number" name="jumlah_bayar"
                                                    class="border rounded px-2 py-1 text-right w-24"
                                                    placeholder="Nominal" min="1000" required>
                                                <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded transition">
                                                    Bayar
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tabel Riwayat Pembayaran -->
            @if (!$tagihansSudahBayar->isEmpty())
                <div class="mt-10">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h3>
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-[600px] w-full bg-white shadow-md rounded-lg text-sm">
                            <thead class="bg-green-50 text-green-700">
                                <tr>
                                    <th class="px-6 py-3 whitespace-nowrap">Iuran</th>
                                    <th class="px-6 py-3 whitespace-nowrap">Jatuh Tempo</th>
                                    <th class="px-6 py-3 whitespace-nowrap">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($tagihansSudahBayar as $tagihan)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium">{{ $tagihan->iuran->nama ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">
                                                <i class="fas fa-check-circle mr-1"></i> Sudah Dibayar
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @endif
    </div>
</x-app-layout>
