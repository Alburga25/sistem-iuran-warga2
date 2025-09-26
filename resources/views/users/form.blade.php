<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name">Nama</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" class="w-full p-2 border rounded">
        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="w-full p-2 border rounded">
        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="noWa">No WA</label>
        <input type="text" name="noWa" id="noWa" value="{{ old('noWa', $user->noWa ?? '') }}" class="w-full p-2 border rounded">
        @error('noWa') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="role">Role</label>
        <select name="role" id="role" class="w-full p-2 border rounded">
            <option value="admin" {{ (old('role', $user->role ?? '') === 'admin') ? 'selected' : '' }}>Admin</option>
            <option value="warga" {{ (old('role', $user->role ?? '') === 'warga') ? 'selected' : '' }}>Warga</option>
        </select>
        @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="password">Password {{ isset($user) ? '(leave blank to keep)' : '' }}</label>
        <input type="password" name="password" id="password" class="w-full p-2 border rounded">
        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>
</div>
