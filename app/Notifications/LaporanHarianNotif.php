<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LaporanHarianNotif extends Notification
{
    use Queueable;

    public $pemasukan;
    public $pengeluaran;
    public $saldo;

    public function __construct($pemasukan, $pengeluaran, $saldo)
    {
        $this->pemasukan = $pemasukan;
        $this->pengeluaran = $pengeluaran;
        $this->saldo = $saldo;
    }

    // Ini ngasih tau Laravel: "Kirim notifnya ke Database aja (dalam web)"
    public function via($notifiable)
    {
        return ['database'];
    }

    // Format pesan yang bakal disimpen ke tabel database dan ditampilin di lonceng
    public function toArray($notifiable)
    {
        return [
            'pesan' => 'Rekap hari ini selesai! Pemasukan Rp ' . number_format($this->pemasukan, 0, ',', '.') . ', Pengeluaran Rp ' . number_format($this->pengeluaran, 0, ',', '.') . '. Saldo bersih: Rp ' . number_format($this->saldo, 0, ',', '.')
        ];
    }
}