<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <--- Ini mesin waktu bawaan Laravel buat ngatur tanggal

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. Nangkep inputan tanggal. Kalau formnya kosong (baru buka), default-nya dari awal sampai akhir bulan ini.
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // 2. Nyari data transaksi punya user yang lagi login, di dalam rentang tanggal tersebut
        $query = Transaction::where('user_id', $userId)
                            ->whereBetween('transaction_date', [$startDate, $endDate]);

        // 3. Ngitung ulang nominal berdasarkan tanggal yang udah difilter
        $pemasukan = (clone $query)->where('type', 'pemasukan')->sum('amount');
        $pengeluaran = (clone $query)->where('type', 'pengeluaran')->sum('amount');
        $saldo = $pemasukan - $pengeluaran;

        // 4. Nampilin 5 riwayat terbaru yang sesuai sama filter
        $recentTransactions = (clone $query)->orderBy('transaction_date', 'desc')
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get();

        // Lempar semua datanya ke halaman depan
        return view('home', compact('pemasukan', 'pengeluaran', 'saldo', 'recentTransactions', 'startDate', 'endDate'));
    }
}