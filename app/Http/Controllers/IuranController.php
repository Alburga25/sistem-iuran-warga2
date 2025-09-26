<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IuranController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        
        $iurans = Iuran::all();
        return view('admin.iurans.index', compact('iurans'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('admin.iurans.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama' => 'required',
        ]);

        Iuran::create($request->all());

        return redirect()->route('admin.iurans.index')->with('success', 'Iuran berhasil ditambahkan.');
    }

    public function edit(Iuran $iuran)
    {
        $this->authorizeAdmin();

        return view('admin.iurans.edit', compact('iuran'));
    }

    public function update(Request $request, Iuran $iuran)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama' => 'required',
        ]);

        $iuran->update($request->all());

        return redirect()->route('admin.iurans.index')->with('success', 'Iuran berhasil diupdate.');
    }

    public function destroy(Iuran $iuran)
    {
        $this->authorizeAdmin();

        $iuran->delete();

        return redirect()->route('admin.iurans.index')->with('success', 'Iuran berhasil dihapus.');
    }

    // ✅ Fungsi Otorisasi untuk Admin
    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}
