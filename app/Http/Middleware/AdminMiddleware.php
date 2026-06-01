<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user udah login DAN apakah dia seorang admin
        if (Auth::check() && Auth::user()->is_admin == true) {
            return $next($request); // Lolos, silahkan masuk
        }

        // Kalau bukan admin, lempar balik ke home dengan pesan error
        return redirect()->route('home')->with('error', 'Gak boleh masuk jir, halaman ini khusus Admin!');
    }
}