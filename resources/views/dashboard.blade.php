<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <h2 class="text-2xl font-bold p-6 text-gray-800">
                    Hello <span class="uppercase">{{ Auth::user()->name }}</span>, Selamat Datang
                </h2>
            </div>

            <!-- Cards Section -->
            <div class="flex justify-center">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow p-5 border border-transparent hover:shadow-lg hover:border-blue-500 transition duration-200">
                        <div class="text-gray-500 text-sm">Jumlah Warga</div>
                        <div class="text-3xl font-bold text-blue-600 mt-1">{{ $jumlahWarga }}</div>
                    </div>

                    <div class="bg-white rounded-xl shadow p-5 border border-transparent hover:shadow-lg hover:border-green-500 transition duration-200">
                        <div class="text-gray-500 text-sm">Warga Sudah Bayar</div>
                        <div class="text-3xl font-bold text-green-600 mt-1">{{ $wargaSudahBayar }}</div>
                    </div>

                    @if (Auth::user()->role == 'admin')
                    <div class="bg-white rounded-xl shadow p-5 border border-transparent hover:shadow-lg hover:border-indigo-500 transition duration-200">
                        <div class="text-gray-500 text-sm">Total Iuran</div>
                        <div class="text-3xl font-bold text-indigo-600 mt-1">Rp {{ number_format($totalTagihanDibayar, 0, ',', '.') }}</div>
                    </div>
                    @endif

                    <div class="bg-white rounded-xl shadow p-5 border border-transparent hover:shadow-lg hover:border-red-500 transition duration-200">
                        <div class="text-gray-500 text-sm">Warga Belum Bayar</div>
                        <div class="text-3xl font-bold text-red-600 mt-1">{{ $wargaBelumBayar }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>