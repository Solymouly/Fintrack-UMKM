<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomResetController extends Controller
{
    // 1. Nampilin form minta reset
    public function index()
    {
        return view('auth.custom_forgot');
    }

    // 2. Ngecek email dan ngirim sinyal ke admin
    public function cekStatus(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan di sistem dawg!');
        }

        // Kalau ternyata UDAH DI-ACC SAMA ADMIN, langsung lempar ke form ganti password
        if ($user->is_reset_approved) {
            return view('auth.custom_reset_form', ['email' => $user->email]);
        }

        // Kalau belum di-ACC, kita kirim status "Minta Reset" ke admin
        $user->is_reset_requested = true;
        $user->save();

        return back()->with('success', 'Permintaan dikirim! Tunggu Admin nge-ACC. Kalau udah di-ACC, masukin emailmu di sini lagi ya.');
    }

    // 3. Proses nyimpen password baru dari UMKM
    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->is_reset_approved) {
            $user->password = Hash::make($request->password);
            $user->is_reset_requested = false; // Bersihin status
            $user->is_reset_approved = false;  // Bersihin status
            $user->save();

            return redirect()->route('login')->with('success', 'Mantap! Password barumu berhasil disimpan. Silahkan Login.');
        }

        return redirect()->route('login')->with('error', 'Akses ditolak jir!');
    }
}