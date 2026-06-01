<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailySummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $pemasukan;
    public $pengeluaran;
    public $saldo;

    public function __construct($user, $pemasukan, $pengeluaran, $saldo)
    {
        $this->user = $user;
        $this->pemasukan = $pemasukan;
        $this->pengeluaran = $pengeluaran;
        $this->saldo = $saldo;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rekap Keuangan Hari Ini - FinTrack UMKM',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_summary',
        );
    }
}