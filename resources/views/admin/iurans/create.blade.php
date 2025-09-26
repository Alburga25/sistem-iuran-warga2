<x-admin-layout>
<h2 class="text-2xl font-bold mb-4">Tambah Iuran Baru</h2>

<form action="{{ route('admin.iurans.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block font-semibold">Nama Iuran</label>
        <input type="text" name="nama" class="w-full border rounded px-4 py-2" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>

</x-admin-layout>
