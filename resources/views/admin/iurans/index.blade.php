<x-admin-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-x">
        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
            });
        </script>
        @endif

        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Daftar Iuran</h2>

        <div class="mb-4">
            <a href="{{ route('admin.iurans.create') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                + Tambah Iuran
            </a>
        </div>
    </div>

    <div class="overflow-x-auto bg-white shadow-md ">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-indigo-100">
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($iurans as $iuran)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $iuran->nama }}</td>
                    <td class="px-4 py-2 flex gap-2">
                        <a href="{{ route('admin.iurans.edit', $iuran) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded transition duration-200"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('admin.iurans.destroy', $iuran) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-red-600 font-bold text-white rounded hover:bg-red-700 transition duration-200">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>