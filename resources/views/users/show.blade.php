<x-admin-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">User Details</h1>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="space-y-4">
            <div>
                <p class="text-lg font-medium text-gray-700"><strong>Name:</strong> {{ $user->name }}</p>
            </div>
            <div>
                <p class="text-lg font-medium text-gray-700"><strong>Email:</strong> {{ $user->email }}</p>
            </div>
            <div>
                <p class="text-lg font-medium text-gray-700"><strong>No WA:</strong> {{ $user->noWa }}</p>
            </div>
            <div>
                <p class="text-lg font-medium text-gray-700"><strong>Role:</strong> {{ $user->role }}</p>
            </div>
        </div>
    </div>

    <a href="{{ route('users.index') }}"
        class="inline-block mt-6 bg-indigo-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-indigo-700 transition duration-200">
        ← Back to User List
    </a>

</x-admin-layout>