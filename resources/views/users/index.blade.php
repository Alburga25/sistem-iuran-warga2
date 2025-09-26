<x-admin-layout>
  <div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-xl">
    @if(session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
      });
    </script>
    @endif

    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Data Pengguna</h2>

    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <!-- Show entries dropdown -->
      <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2 bg-gray-50 border border-gray-200 px-4 py-2 rounded-md shadow-sm">
        <label for="perPage" class="text-sm font-medium text-gray-700 whitespace-nowrap">Tampilkan</label>

        <select
          name="perPage"
          id="perPage"
          onchange="this.form.submit()"
          class="block w-full py-1.5 px-3 pr-8 border border-gray-300 rounded-md text-sm text-gray-700 focus:ring-indigo-500 focus:border-indigo-500">
          @foreach ([10, 25, 50, 100] as $size)
          <option value="{{ $size }}" {{ request('perPage') == $size ? 'selected' : '' }}>
            {{ $size }}
          </option>
          @endforeach
        </select>


        <span class="text-sm font-medium text-gray-700">data</span>
      </form>


      <!-- Tombol Tambah User -->
      <a href="{{ route('users.create') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        + Tambah User
      </a>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-indigo-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No WA</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach ($users as $user)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->noWa }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 capitalize">{{ $user->role }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm flex gap-2">
              <a href="{{ route('users.show', $user) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 rounded transition duration-200"><i class="fa-solid fa-eye"></i></a>
              <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded transition duration-200"><i class="fa-solid fa-pen-to-square"></i></a>
              <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')" class="inline-block">
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

    <div class="mt-4">
      {{ $users->appends(['perPage' => request('perPage')])->links() }}
    </div>
  </div>
</x-admin-layout>