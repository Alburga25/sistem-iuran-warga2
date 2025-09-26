<x-admin-layout>
<h2 class="text-2xl font-bold mb-4">Edit Iuran</h2>

<form action="{{ route('admin.iurans.update', $iuran) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
        <label class="block font-semibold">Nama Iuran</label>
        <input type="text" name="nama" value="{{ $iuran->nama }}" class="w-full border rounded px-4 py-2" required>
    </div>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
</form>

</x-admin-layout>
