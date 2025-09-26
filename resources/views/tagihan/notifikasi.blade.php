<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
            });
        </script>
        @endif

        <h1 class="text-3xl font-bold mb-6 text-gray-900">Kirim Notifikasi WA</h1>

        <form action="{{ route('trigger.notifikasi.wa') }}" method="POST" onsubmit="return confirm('Kirim notifikasi WA ke semua yang belum bayar?')">
            @csrf
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Kirim Notifikasi WA Tagihan Belum Bayar
            </button>
        </form>
    </div>
</x-app-layout>
