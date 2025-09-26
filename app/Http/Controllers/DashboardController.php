<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tagihan;
use App\Models\Iuran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah warga dengan role 'warga' (hanya warga)
        $jumlahWarga = User::where('role', 'warga')->count();

        // Hitung jumlah warga yang sudah dan belum membayar
        $wargaSudahBayar = Tagihan::where('status', 'sudah_bayar')
            ->whereHas('user', function ($query) {
                $query->where('role', 'warga');
            })
            ->distinct('user_id')
            ->count();

        $wargaBelumBayar = $jumlahWarga - $wargaSudahBayar;

        // Mendapatkan total seluruh tagihan yang telah dibayar untuk warga
        $totalTagihanDibayar = Tagihan::where('status', 'sudah_bayar')
            ->whereHas('user', function ($query) {
                $query->where('role', 'warga');
            })
            ->sum('jumlah_bayar'); // ✅ pakai kolom dari tabel tagihans


        return view('dashboard', compact(
            'jumlahWarga',
            'wargaSudahBayar',
            'wargaBelumBayar',
            'totalTagihanDibayar'
        ));
    }
}
