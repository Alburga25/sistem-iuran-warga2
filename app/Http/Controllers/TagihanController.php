<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Iuran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;

class TagihanController extends Controller
{
    private function checkAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $bulan = $request->input('bulan', now()->month);
            $tahun = $request->input('tahun', now()->year);
            // $forceUpdate = $request->boolean('force_update', true); 

            // if ($forceUpdate) {
            //     Tagihan::where('user_id', $user->id)
            //         ->whereMonth('tanggal_jatuh_tempo', $bulan)
            //         ->whereYear('tanggal_jatuh_tempo', $tahun)
            //         ->delete();
            // }

            $tagihans = Tagihan::where('user_id', $user->id)
                ->whereMonth('tanggal_jatuh_tempo', $bulan)
                ->whereYear('tanggal_jatuh_tempo', $tahun)
                ->get();

            if ($tagihans->isEmpty()) {
                $iuran = Iuran::all();
                foreach ($iuran as $item) {
                    Tagihan::create([
                        'user_id' => $user->id,
                        'iuran_id' => $item->id,
                        'tanggal_jatuh_tempo' => Carbon::create($tahun, $bulan, 1),
                        'status' => 'belum_bayar'
                    ]);
                }

                $tagihans = Tagihan::where('user_id', $user->id)
                    ->whereMonth('tanggal_jatuh_tempo', $bulan)
                    ->whereYear('tanggal_jatuh_tempo', $tahun)
                    ->get();
            }

            // Cek status Midtrans
            foreach ($tagihans as $tagihan) {
                if ($tagihan->order_id) {
                    try {
                        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                        $statusResponse = \Midtrans\Transaction::status($tagihan->order_id);

                        if (is_object($statusResponse) && isset($statusResponse->transaction_status)) {
                            $statusTagihan = in_array($statusResponse->transaction_status, ['settlement', 'capture'])
                                ? 'sudah_bayar'
                                : 'belum_bayar';

                            $tagihan->update(['status' => $statusTagihan]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Gagal memeriksa status Midtrans untuk tagihan ID {$tagihan->id}: " . $e->getMessage());
                    }
                }
            }

            $tagihansBelumBayar = $tagihans->where('status', 'belum_bayar');
            $tagihansSudahBayar = $tagihans->where('status', 'sudah_bayar');

            return view('tagihan.index', compact('tagihansBelumBayar', 'tagihansSudahBayar', 'bulan', 'tahun'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat tagihan: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data tagihan.');
        }
    }


    public function generateBulanan()
    {
        try {
            $iurans = Iuran::all();
            $users = \App\Models\User::whereIn('role', ['warga', 'admin'])->get();
            $bulanIni = Carbon::now();

            foreach ($users as $user) {
                foreach ($iurans as $iuran) {
                    $existing = Tagihan::where('user_id', $user->id)
                        ->where('iuran_id', $iuran->id)
                        ->whereMonth('tanggal_jatuh_tempo', $bulanIni->month)
                        ->whereYear('tanggal_jatuh_tempo', $bulanIni->year)
                        ->first();

                    if (!$existing) {
                        Tagihan::create([
                            'user_id'             => $user->id,
                            'iuran_id'            => $iuran->id,
                            'tanggal_jatuh_tempo' => $bulanIni->endOfMonth(),
                            'status'              => 'belum_bayar',
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Tagihan bulanan berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Gagal membuat tagihan bulanan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat tagihan.');
        }
    }

    public function bayar(Request $request, Tagihan $tagihan)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Ambil nominal dari input user
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1000',
        ]);

        $jumlahBayar = $request->jumlah_bayar;

        // Membuat order ID unik
        $orderId = 'TAGIHAN-' . $tagihan->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $jumlahBayar, // ✅ gunakan nominal input user
            ],
            'customer_details' => [
                'first_name' => $tagihan->user->name,
                'email' => $tagihan->user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error("Error getting Snap token: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }

        // Simpan order_id + jumlah_bayar ke database
        $tagihan->update([
            'order_id' => $orderId,
            'snap_token' => $snapToken,
            'jumlah_bayar' => $jumlahBayar, // ✅ simpan ke tabel tagihans
            'status' => 'belum_bayar'
        ]);

        return view('tagihan.bayar', compact('tagihan', 'snapToken'));
    }


    public function updateStatus(Request $request)
    {
        // Validasi input request
        $validated = $request->validate([
            'order_id' => 'required|string',
            'transaction_status' => 'required|string|in:capture,settlement,failed,pending,cancel,deny',
        ]);

        Log::info("Webhook diterima: Order ID: {$validated['order_id']}, Status: {$validated['transaction_status']}");

        try {
            // Temukan tagihan berdasarkan order_id
            $tagihan = Tagihan::where('order_id', $validated['order_id'])->first();

            if (!$tagihan) {
                Log::warning("Tagihan dengan Order ID {$validated['order_id']} tidak ditemukan.");
                return response()->json(['message' => 'Tagihan tidak ditemukan.'], 404);
            }

            // Menentukan status tagihan berdasarkan status transaksi
            $statusTagihan = in_array($validated['transaction_status'], ['settlement', 'capture'])
                ? 'sudah_bayar'
                : 'belum_bayar';

            // Memperbarui status tagihan
            $tagihan->update(['status' => $statusTagihan]);

            // Log hasil update
            Log::info("Status tagihan ID {$tagihan->id} diperbarui menjadi: {$statusTagihan}");

            return response()->json(['message' => 'Status tagihan berhasil diperbarui.']);
        } catch (\Exception $e) {
            Log::error('Error updating tagihan status: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui status tagihan.'], 500);
        }
    }

    public function buktiPembayaran()
    {
        $user = Auth::user();
        $tagihans = Tagihan::where('user_id', $user->id)
            ->whereNotNull('order_id')
            ->latest() // Urutkan dari yang terbaru
            ->paginate(5); // 5 per halaman

        foreach ($tagihans as $tagihan) {
            if ($tagihan->order_id) {
                try {
                    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                    $statusResponse = \Midtrans\Transaction::status($tagihan->order_id);

                    if (is_object($statusResponse) && isset($statusResponse->transaction_status)) {
                        $statusTagihan = in_array($statusResponse->transaction_status, ['settlement', 'capture'])
                            ? 'sudah_bayar'
                            : 'belum_bayar';

                        $tagihan->status = $statusTagihan;

                        // Log settlement_time untuk memeriksa apakah ada
                        if ($statusTagihan == 'sudah_bayar' && isset($statusResponse->settlement_time)) {
                            Log::info("Tagihan ID {$tagihan->id} settlement_time: " . $statusResponse->settlement_time);
                            $tagihan->settlement_time = \Carbon\Carbon::parse($statusResponse->settlement_time)->timezone('Asia/Jakarta');
                        } else {
                            $tagihan->settlement_time = null;
                        }


                        $tagihan->save();
                    }
                } catch (\Exception $e) {
                    Log::error("Gagal memeriksa status Midtrans untuk tagihan ID {$tagihan->id}: " . $e->getMessage());
                }
            }
        }

        return view('tagihan.bukti-pembayaran', compact('tagihans'));
    }

    public function laporan(Request $request)
    {
        if (!$this->checkAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $tagihansSudahBayar = Tagihan::with('user', 'iuran')
            ->where('status', 'sudah_bayar')
            ->whereMonth('tanggal_jatuh_tempo', $bulan)
            ->whereYear('tanggal_jatuh_tempo', $tahun)
            ->orderBy('tanggal_jatuh_tempo', 'desc')
            ->paginate(5, ['*'], 'sudah_bayar_page');

        $tagihansBulanLain = Tagihan::with('user', 'iuran')
            ->where('status', 'sudah_bayar')
            ->where(function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal_jatuh_tempo', '!=', $bulan)
                    ->orWhereYear('tanggal_jatuh_tempo', '!=', $tahun);
            })
            ->orderBy('tanggal_jatuh_tempo', 'desc')
            ->paginate(10, ['*'], 'bulan_lain_page');

        // ✅ hitung total
        $totalBulanIni = Tagihan::where('status', 'sudah_bayar')
            ->whereMonth('tanggal_jatuh_tempo', $bulan)
            ->whereYear('tanggal_jatuh_tempo', $tahun)
            ->sum('jumlah_bayar');

        $totalBulanLain = Tagihan::where('status', 'sudah_bayar')
            ->where(function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal_jatuh_tempo', '!=', $bulan)
                    ->orWhereYear('tanggal_jatuh_tempo', '!=', $tahun);
            })
            ->sum('jumlah_bayar');

        return view('tagihan.laporan', compact(
            'tagihansSudahBayar',
            'tagihansBulanLain',
            'bulan',
            'tahun',
            'totalBulanIni',
            'totalBulanLain'
        ));
    }

    // Fungsi untuk memeriksa dan memperbarui status dan tanggal bayar berdasarkan status dari Midtrans
    private function updateStatusTanggalBayar($tagihans)
    {
        foreach ($tagihans as $tagihan) {
            if ($tagihan->order_id) {
                try {
                    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                    $statusResponse = \Midtrans\Transaction::status($tagihan->order_id);

                    if (is_object($statusResponse) && isset($statusResponse->transaction_status)) {
                        $statusTagihan = in_array($statusResponse->transaction_status, ['settlement', 'capture'])
                            ? 'sudah_bayar'
                            : 'belum_bayar';

                        $tagihan->status = $statusTagihan;

                        // Jika sudah bayar, ambil settlement_time dan simpan sebagai tanggal bayar
                        if ($statusTagihan == 'sudah_bayar' && isset($statusResponse->settlement_time)) {
                            $tagihan->settlement_time = \Carbon\Carbon::parse($statusResponse->settlement_time)->timezone('Asia/Jakarta');
                        } else {
                            $tagihan->settlement_time = null;
                        }

                        $tagihan->save();
                    }
                } catch (\Exception $e) {
                    Log::error("Gagal memeriksa status Midtrans untuk tagihan ID {$tagihan->id}: " . $e->getMessage());
                }
            }
        }
    }

    public function semuaWargaTagihan(Request $request)
    {
        if (!$this->checkAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        // Hanya warga yang diambil (diurutkan dari yang terbaru)
        $wargas = User::where('role', 'warga')
            ->latest()
            ->paginate(10);

        $data = [];

        foreach ($wargas as $warga) {
            // Ambil tagihan warga ini pada bulan & tahun yang diminta
            $tagihans = Tagihan::with('iuran')
                ->where('user_id', $warga->id)
                ->whereMonth('tanggal_jatuh_tempo', $bulan)
                ->whereYear('tanggal_jatuh_tempo', $tahun)
                ->orderBy('tanggal_jatuh_tempo', 'desc')
                ->get();

            // Perbarui status tagihan berdasarkan status Midtrans
            $this->updateStatusTagihanMidtrans($tagihans);

            // Jika belum ada tagihan, buat tagihan untuk semua iuran
            if ($tagihans->isEmpty()) {
                $iurans = Iuran::all();
                foreach ($iurans as $iuran) {
                    $tagihan = Tagihan::create([
                        'user_id' => $warga->id,
                        'iuran_id' => $iuran->id,
                        'tanggal_jatuh_tempo' => Carbon::create($tahun, $bulan, 1),
                        'status' => 'belum_bayar',
                        'jumlah_bayar' => null, 
                    ]);
                    $tagihans->push($tagihan);
                }
            }


            // Pisahkan tagihan berdasarkan status
            $belumBayar = $tagihans->where('status', 'belum_bayar');
            $sudahBayar = $tagihans->where('status', 'sudah_bayar');

            $data[] = [
                'warga' => $warga,
                'tagihan_belum_bayar' => $belumBayar,
                'tagihan_sudah_bayar' => $sudahBayar,
            ];
        }

        return view('tagihan.semua-warga', compact('data', 'bulan', 'tahun', 'wargas'));
    }

    // Method untuk memperbarui status tagihan dari Midtrans
    private function updateStatusTagihanMidtrans($tagihans)
    {
        foreach ($tagihans as $tagihan) {
            if ($tagihan->order_id) {
                try {
                    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                    $statusResponse = \Midtrans\Transaction::status($tagihan->order_id);

                    if (is_object($statusResponse) && isset($statusResponse->transaction_status)) {
                        $statusTagihan = in_array($statusResponse->transaction_status, ['settlement', 'capture'])
                            ? 'sudah_bayar'
                            : 'belum_bayar';

                        $tagihan->status = $statusTagihan;

                        if ($statusTagihan == 'sudah_bayar' && isset($statusResponse->settlement_time)) {
                            $tagihan->settlement_time = \Carbon\Carbon::parse($statusResponse->settlement_time)->timezone('Asia/Jakarta');
                        } else {
                            $tagihan->settlement_time = null;
                        }

                        $tagihan->save();
                    }
                } catch (\Exception $e) {
                    Log::error("Gagal memeriksa status Midtrans untuk tagihan ID {$tagihan->id}: " . $e->getMessage());
                }
            }
        }
    }

    public function triggerNotifikasiWABelumBayar()
    {
        $now = Carbon::now();
        $users = User::whereHas('tagihans', function ($query) use ($now) {
            $query->where('status', 'belum_bayar')
                ->whereYear('tanggal_jatuh_tempo', $now->year)
                ->whereMonth('tanggal_jatuh_tempo', $now->month);
        })->get();

        Log::info('Trigger notifikasi WA Fonnte dimulai');

        foreach ($users as $user) {
            $phone = $user->noWa;

            // Normalisasi nomor hp
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            } elseif (!str_starts_with($phone, '62')) {
                $phone = '62' . $phone;
            }

            // Ambil tanggal jatuh tempo dari tagihan bulan ini
            $tagihan = $user->tagihans()
                ->where('status', 'belum_bayar')
                ->whereYear('tanggal_jatuh_tempo', $now->year)
                ->whereMonth('tanggal_jatuh_tempo', $now->month)
                ->first();

            $tanggalJatuhTempo = $tagihan ? Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d F Y') : '-';

            $websiteUrl = 'https://sistem-iuran-warga2.vercel.app/';

            $pesan = "Halo, {$user->name}. Anda memiliki tagihan iuran bulan ini yang belum dibayar. "
                . "Mohon segera melunasi sebelum tanggal *{$tanggalJatuhTempo}*. "
                . "Info lengkap kunjungi: {$websiteUrl} untuk membayar tagihan iuran anda.";

            Log::info("Mengirim WA ke: {$phone}");

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query([
                    'target' => $phone,
                    'message' => $pesan,
                    'countryCode' => '62',
                    'schedule' => 0,
                    'typing' => false,
                    'delay' => '2',
                    'multi' => true
                ]),
                CURLOPT_HTTPHEADER => [
                    'Authorization: 735fSfmrmVtCtfHSNQ1Y' // Token Fonnte
                ],
            ]);

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
                Log::error("Gagal kirim WA ke {$phone}. Error: {$error_msg}");
            } else {
                Log::info("Sukses kirim WA ke {$phone}. Response: {$response}");
            }

            curl_close($curl);
        }

        return redirect()->back()->with('success', 'Notifikasi WA sudah diproses, cek log jika ada error.');
    }
}
