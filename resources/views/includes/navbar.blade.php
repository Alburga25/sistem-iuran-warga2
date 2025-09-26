<!-- Header -->
<header id="header" class="bg-white py-5 sticky z-30 top-0 w-full">
    <nav class="container mx-auto flex justify-between items-center md:px-8 px-4 gap-8">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="w-[200px]">
            <h1 class="font-medium text-lg">Cuci Mobil</h1>
        </a>

        <!-- Navbar Links -->
        <ul id="menu" class="hidden md:flex md:py-0 py-3 gap-5 md:justify-center md:items-center md:static absolute top-full left-0 w-full bg-white md:bg-transparent shadow-md md:shadow-none flex-col md:flex-row text-center font-medium text-black">
            <li><a href="{{ route('home') }}" class="block py-3 md:py-0 px-4 hover:bg-white no-underline md:hover:bg-transparent hover:text-blue-400 transition">Beranda</a></li>
            <li><a href="#tentang-kami" class="block py-3 md:py-0 px-4 hover:bg-white no-underline md:hover:bg-transparent hover:text-blue-400 transition">Tentang Kami</a></li>
            <li><a href="{{ route('reservasi.create') }}" class="block py-3 md:py-0 px-4 hover:bg-white no-underline md:hover:bg-transparent hover:text-blue-400 transition">Reservasi</a></li>
            <li><a href="#kontak" class="block py-3 md:py-0 px-4 hover:bg-white no-underline md:hover:bg-transparent hover:text-blue-400 transition">Kontak</a></li>
            <li><a href="{{ url('/dashboard') }}" class="block py-3 md:py-0 px-4 hover:bg-white no-underline md:hover:bg-transparent hover:text-blue-400 transition">Admin</a></li>
            <div class="w-[150px] space-y-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-left font-semibold bg-gray-200 rounded px-3 py-2 text-gray-600 hover:text-red-600 w-full">
                        Log Out
                    </button>
                </form>
            </div>
        </ul>

        <!-- Hamburger Menu Button -->
        <button class="md:hidden flex items-center text-black cursor-pointer focus:outline-none" id="menu-toggle">
            <svg id="menu-icon" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg id="close-icon" class="hidden w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </nav>
</header>