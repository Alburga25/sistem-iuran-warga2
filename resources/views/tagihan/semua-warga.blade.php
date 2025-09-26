<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                });
            </script>
        @endif
        <h1 class="text-3xl font-bold mb-6 text-gray-900">Tagihan Semua Warga</h1>

        <form method="GET" action="{{ route('tagihan.semuaWarga') }}" class="mb-8 flex flex-wrap gap-4 items-center">
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                <select name="bulan" id="bulan"
                    class="mt-1 block w-36 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach (range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                <select name="tahun" id="tahun"
                    class="mt-1 block w-36 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach (range(date('Y') - 5, date('Y') + 1) as $t)
                        <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Filter</button>
            </div>
        </form>

        @forelse($data as $item)
            <div class="mb-6 rounded-lg shadow-md bg-white p-4 border">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $item['warga']->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $item['warga']->email }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach ($item['tagihan_belum_bayar'] as $tagihan)
                        <div
                            class="flex items-center justify-between bg-yellow-50 p-3 rounded-md border-l-4 border-yellow-400">
                            <div>
                                <p class="font-semibold">{{ $tagihan->iuran->nama }}</p>
                                <p class="text-sm text-gray-500">Jatuh Tempo:
                                    {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <span class="px-2 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded">Belum
                                    Bayar</span>
                                <p class="text-sm font-semibold">
                                    Rp
                                    {{ $tagihan->jumlah_bayar ? number_format($tagihan->jumlah_bayar, 0, ',', '.') : '-' }}
                                </p>

                            </div>
                        </div>
                    @endforeach

                    @foreach ($item['tagihan_sudah_bayar'] as $tagihan)
                        <div
                            class="flex items-center justify-between bg-green-50 p-3 rounded-md border-l-4 border-green-400">
                            <div>
                                <p class="font-semibold">{{ $tagihan->iuran->nama }}</p>
                                <p class="text-sm text-gray-500">Jatuh Tempo:
                                    {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-sm text-gray-500">Bayar:
                                    {{ $tagihan->settlement_time ? $tagihan->settlement_time->format('d-m-Y H:i') : '-' }}
                                </p>
                            </div>
                            <div>
                                <span class="px-2 py-1 text-xs font-medium text-green-600 bg-green-100 rounded">Sudah
                                    Bayar</span>
                                <p class="text-sm font-semibold">
                                    Rp
                                    {{ $tagihan->jumlah_bayar ? number_format($tagihan->jumlah_bayar, 0, ',', '.') : '-' }}
                                </p>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-gray-600 italic">Tidak ada data warga.</p>
        @endforelse

        <div class="mt-6">
            {{ $wargas->links() }}
        </div>
    </div>
</x-app-layout>
