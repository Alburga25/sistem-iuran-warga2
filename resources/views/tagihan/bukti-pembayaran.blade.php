<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Bukti Pembayaran</h2>

        @if($tagihans->isEmpty())
        <div class="flex items-center justify-center bg-yellow-50 text-yellow-800 p-6 rounded-lg shadow-md">
            <p class="font-semibold text-lg">Anda belum memiliki bukti pembayaran.</p>
        </div>
        @else
        <div class="mt-10 overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">Iuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">Tanggal Bayar</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tagihans as $tagihan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                            {{ $tagihan->iuran->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            {{ $tagihan->settlement_time ? $tagihan->settlement_time->format('d-m-Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($tagihan->status == 'sudah_bayar')
                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-green-800 bg-green-100 rounded-full">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Sudah Dibayar
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-red-800 bg-red-100 rounded-full">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Belum Dibayar
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="my-6 flex justify-center">
            <nav aria-label="Page navigation">
                <ul class="inline-flex items-center space-x-2">
                    <!-- Tombol Previous -->
                    @if ($tagihans->onFirstPage())
                    <li>
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">← prev</span>
                    </li>
                    @else
                    <li>
                        <a href="{{ $tagihans->previousPageUrl() }}"
                            class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition">← prev</a>
                    </li>
                    @endif

                    <!-- Tombol Halaman -->
                    @foreach ($tagihans->getUrlRange(1, $tagihans->lastPage()) as $page => $url)
                    <li>
                        <a href="{{ $url }}"
                            class="px-4 py-2 rounded-lg 
                                  {{ $page == $tagihans->currentPage() ? 'bg-green-600 text-white' : 'bg-gray-200 text-green-600 hover:bg-green-100' }}
                                  transition font-semibold">
                            {{ $page }}
                        </a>
                    </li>
                    @endforeach

                    <!-- Tombol Next -->
                    @if ($tagihans->hasMorePages())
                    <li>
                        <a href="{{ $tagihans->nextPageUrl() }}"
                            class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition">next →</a>
                    </li>
                    @else
                    <li>
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">next →</span>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</x-app-layout>