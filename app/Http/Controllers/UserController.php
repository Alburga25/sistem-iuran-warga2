<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $perPage = $request->get('perPage', 10);
        $users = User::orderBy('created_at', 'DESC')->paginate($perPage);

        return view('users.index', compact('users'));
    }


    public function create()
    {
        $this->authorizeAdmin();
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'noWa' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $this->authorizeAdmin();
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'noWa' => 'required|string|max:20',
            'role' => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}
