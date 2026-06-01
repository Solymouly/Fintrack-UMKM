<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use App\Notifications\LaporanHarianNotif;
use Carbon\Carbon;

class SendDailySummary extends Command
{
    protected $signature = 'app:send-daily-summary';
    protected $description = 'Kirim notifikasi rekap transaksi harian ke lonceng UMKM';

    public function handle()
    {
        $this->info('Mulai menghitung dan mengirim notifikasi lonceng harian...');

        $users = User::where('is_admin', false)->get();
        $today = Carbon::now()->format('Y-m-d');

        foreach ($users as $user) {
            $pemasukan = Transaction::where('user_id', $user->id)
                ->whereDate('transaction_date', $today)
                ->where('type', 'pemasukan')
                ->sum('amount');

            $pengeluaran = Transaction::where('user_id', $user->id)
                ->whereDate('transaction_date', $today)
                ->where('type', 'pengeluaran')
                ->sum('amount');

            $saldo = $pemasukan - $pengeluaran;

            // JRENG! Ini yang berubah. Ngirim notif langsung ke user
            $user->notify(new LaporanHarianNotif($pemasukan, $pengeluaran, $saldo));
            
            $this->info('Notif lonceng terkirim ke: ' . $user->name);
        }

        $this->info('Selesai ngirim semua notifikasi ke web!');
    }
}