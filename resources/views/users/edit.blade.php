<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Edit User</h1>

    <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        @include('users.form', ['user' => $user])

        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded mt-4">Update</button>
    </form>
</x-admin-layout>