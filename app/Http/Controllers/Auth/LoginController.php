<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // Tambahkan fungsi ini buat misahin tujuan setelah login
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if ($user->is_admin) {
            // Kalau dia admin, arahin ke rute admin.dashboard
            return redirect()->route('admin.dashboard'); 
        }

        // Kalau user UMKM biasa, arahin ke home
        return redirect('/home');
    }


}
