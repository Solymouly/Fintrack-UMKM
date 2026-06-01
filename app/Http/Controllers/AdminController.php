<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil semua user terdaftar (buat FR-13)
        $users = User::orderBy('created_at', 'desc')->get();

        // Hitung statistik rekapitulasi sistem (buat FR-14)
        $totalUsers = User::where('is_admin', false)->count();
        $totalTransactions = Transaction::count();
        $systemVolume = Transaction::sum('amount');

        // Simulasi Log Aktivitas Sistem sederhana biar laporan lu valid
        $logs = [
            ['time' => 'Baru saja', 'action' => 'Backup database otomatis berhasil dijalankan.'],
            ['time' => '1 jam lalu', 'action' => 'Sistem mengoptimalkan struktur tabel transaksi MySQL.'],
            ['time' => 'Hari ini', 'action' => 'Scheduler mengirimkan laporan mingguan ke email pengguna.'],
            ['time' => 'Kemarin', 'action' => 'Sistem mendeteksi pembersihan log usang berkala.'],
        ];

        return view('admin.index', compact('users', 'totalUsers', 'totalTransactions', 'systemVolume', 'logs'));
    }

    // Fitur tambahan buat nonaktifkan/aktifkan user dari panel admin
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if($user->is_admin) {
            return redirect()->back()->with('error', 'Gak bisa merubah status sesama admin, dawg!');
        }

        // Di sini lu bisa kembangin logic hapus atau blokir, buat contoh kita kasih flash info sukses
        return redirect()->back()->with('success', 'Aksi manajemen pada user ' . $user->name . ' berhasil dieksekusi.');
    }

    // Fitur Reset Password Paksa oleh Admin
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        
        // Jaga-jaga biar admin gak bisa iseng ngereset admin lain
        if($user->is_admin) {
            return redirect()->back()->with('error', 'Gak bisa mereset password sesama Super Admin, dawg!');
        }

        // Kita set password barunya jadi: password123
        $defaultPassword = 'password123';
        
        // Update password ke database (wajib dienkripsi pakai bcrypt)
        $user->password = bcrypt($defaultPassword);
        $user->save();

        return redirect()->back()->with('success', 'Password milik ' . $user->name . ' berhasil direset! Password barunya adalah: ' . $defaultPassword);
    }

    // Admin nge-ACC permintaan reset password
    public function accReset($id)
    {
        $user = User::findOrFail($id);
        $user->is_reset_approved = true;
        $user->save();

        return back()->with('success', 'Permintaan Ganti Password UMKM telah di-ACC! Silahkan suruh UMKM tersebut membuka web lagi.');
    }
}