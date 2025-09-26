<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Create New User</h1>

    <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        @include('users.form')

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded mt-4">Save</button>
    </form>
</x-admin-layout>
