<!-- Sidebar -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-gray-900 border-r border-gray-200 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0 min-h-screen">

    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-4 border-b">
        <div class="text-xl font-bold">
            <span class="text-white">Sistem Informasi Iuran</span>
            <h2 class=" text-green-500">Antara Residence</h2>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-100 hover:text-gray-200">
            ✕
        </button>
    </div>

    <!-- Menu -->
    <nav class="mt-5 space-y-2 px-4">
        <p class="text-gray-100 font-semibold">Menu</p>

        <a href="{{ route('dashboard') }}"
            class="block transition duration-200 rounded px-3 py-2 font-medium
                  {{ request()->routeIs('dashboard') 
                      ? 'bg-gray-700 text-white font-semibold' 
                      : 'text-gray-100 hover:bg-gray-200 hover:text-gray-700' }}">
            Dashboard
        </a>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('admin.iurans.index') }}"
            class="block transition duration-200 rounded px-3 py-2 font-medium
                  {{ request()->routeIs('admin.iurans.index') 
                      ? 'bg-gray-700 text-white font-semibold' 
                      : 'text-gray-100 hover:bg-gray-200 hover:text-gray-700' }}">
            Iuran Pembayaran
        </a>
        @endif
        
        <a href="{{ route('tagihan.index') }}"
            class="block transition duration-200 rounded px-3 py-2 font-medium
                  {{ request()->routeIs('tagihan.index') 
                      ? 'bg-gray-700 text-white font-semibold' 
                      : 'text-gray-100 hover:bg-gray-200 hover:text-gray-700' }}">
            Tagihan
        </a>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('tagihan.semuaWarga') }}"
            class="block transition duration-200 rounded px-3 py-2 font-medium
                  {{ request()->routeIs('tagihan.semuaWarga') 
                      ? 'bg-gray-700 text-white font-semibold' 
                      : 'text-gray-100 hover:bg-gray-200 hover:text-gray-700' }}">
            Tagihan Warga
        </a>


        <a href="{{ route('tagihan.notifikasi') }}"
            class="block transition duration-200 rounded px-3 py-2 font-medium
          {{ request()->routeIs('tagihan.notifikasi') 
              ? 'bg-gray-700 text-white font-semibold' 
              : 'text-gray-100 hover:bg-gray-200 hover:text-gray-700' }}">
            Notifikasi
        </a>

        <a href="{{ route('users.index') }}"
            class="block transition duration-200 rounded px-3 py-2 font-medium
                  {{ request()->routeIs('users.index') 
                      ? 'bg-gray-700 text-white font-semibold' 
                      : 'text-gray-100 hover:bg-gray-200 hover:text-gray-700' }}">
            List Warga
        </a>
        <a href="{{ route('tagihan.laporan') }}"
            class="block transition duration-200 rounded px-3 py-2 font-medium
                  {{ request()->routeIs('tagihan.laporan') 
                      ? 'bg-gray-700 text-white font-semibold' 
                      : 'text-gray-100 hover:bg-gray-200 hover:text-gray-700' }}">
            Laporan Keuangan
        </a>
        @endif
        <a href="{{ route('tagihan.bukti-pembayaran') }}"
            class="block transition duration-200 rounded px-3 py-2 font-medium
                  {{ request()->routeIs('tagihan.bukti-pembayaran') 
                      ? 'bg-gray-700 text-white font-semibold' 
                      : 'text-gray-100 hover:bg-gray-200 hover:text-gray-700' }}">
            Bukti Pembayaran
        </a>
    </nav>

    <!-- Aksi -->
    <div class="border-t my-5 pt-4 px-4">
        <p class="text-gray-100 font-semibold">Aksi</p>
        <div class="mt-4 w-[150px] space-y-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-left font-semibold bg-gray-200 rounded px-3 py-2 text-gray-600 hover:text-red-600 w-full">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>