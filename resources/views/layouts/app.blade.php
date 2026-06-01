<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FinTrack UMKM') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* Mengganti font dasar dan warna background */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6; 
            color: #2c3e50;
        }

        /* Tema Warna FinTrack dari Logo Baru Lu */
        .bg-fintrack {
            background: linear-gradient(135deg, #427ce0 0%, #1e4596 100%) !important;
        }
        .text-fintrack {
            color: #427ce0 !important;
        }
        .btn-fintrack {
            background: linear-gradient(135deg, #427ce0 0%, #1e4596 100%);
            color: white;
            border: none;
        }
        .btn-fintrack:hover {
            background: linear-gradient(135deg, #3565b8 0%, #153370 100%);
            color: white;
        }

        /* Navbar Styling */
        .navbar {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        /* Custom warna teks navbar biar nyala di background biru */
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        .navbar-dark .navbar-nav .nav-link:hover, 
        .navbar-dark .navbar-nav .nav-link.active {
            color: #ffffff !important;
        }

        /* Card Global Styling */
        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.08) !important;
        }

        /* Input Form & Button Styling */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.6rem 1rem;
            border: 1px solid #dee2e6;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(66, 124, 224, 0.25);
            border-color: #427ce0;
        }
        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            transition: all 0.2s;
        }
        
        /* Modifikasi tabel biar lebih elegan */
        .table > :not(caption) > * > * {
            padding: 1rem 1rem;
            border-bottom-color: #f1f3f5;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-fintrack sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/home') }}">
                    <img src="{{ asset('images/logo-white.png') }}" alt="FinTrack Logo" height="35" class="me-2">
                    <span class="fw-bold text-white fs-4" style="letter-spacing: 0.5px;">FinTrack</span>
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto ps-4">
                        @auth
                            @if(!Auth::user()->is_admin)
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('transactions.index') ? 'active fw-bold' : '' }}" href="{{ route('transactions.index') }}">Transaksi</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-white fw-medium" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-light text-fintrack fw-bold ms-2 rounded-pill px-4 py-2" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else

                            @if(Auth::check() && !Auth::user()->is_admin)
                            <li class="nav-item dropdown me-3 mt-1">
                                <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell-fill fs-5"></i>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                            {{ Auth::user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" aria-labelledby="notifDropdown" style="width: 320px;">
                                    <div class="card border-0">
                                        <div class="card-header bg-fintrack text-white fw-bold border-0">
                                            <i class="bi bi-bell me-1"></i> Notifikasi Harian
                                        </div>
                                        <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                                            @forelse(Auth::user()->notifications as $notif)
                                                <div class="p-3 border-bottom {{ $notif->unread() ? 'bg-light' : 'bg-white' }}">
                                                    <p class="mb-1 small">{{ $notif->data['pesan'] }}</p>
                                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                                </div>
                                            @empty
                                                <div class="p-4 text-center text-muted small">
                                                    <i class="bi bi-bell-slash fs-4 d-block mb-2 text-black-50"></i>
                                                    Belum ada notifikasi rekap.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-medium text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="navbarDropdown">
                                    
                                    @if(Auth::check() && Auth::user()->is_admin)
                                        <a class="dropdown-item text-danger fw-bold" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-shield-lock me-1"></i> Panel Super Admin
                                        </a>
                                        <hr class="dropdown-divider">
                                    @endif

                                    @if(Auth::check() && !Auth::user()->is_admin)
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalGantiPassword">
                                            <i class="bi bi-key me-1"></i> Ganti Password
                                        </a>
                                        <hr class="dropdown-divider">
                                    @endif

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            @yield('content')
        </main>
    </div>

    @auth
        @if(!Auth::user()->is_admin)
        <div class="modal fade" id="modalGantiPassword" tabindex="-1" aria-labelledby="modalGantiPasswordLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <form action="#" method="POST">
                        @csrf
                        <div class="modal-header bg-fintrack text-white border-0">
                            <h5 class="modal-title fw-bold" id="modalGantiPasswordLabel"><i class="bi bi-shield-lock me-2"></i>Buat Password Baru</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Password Lama / Sementara</label>
                                <input type="password" name="password_lama" class="form-control" required placeholder="Masukkan password saat ini">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Password Baru (Rahasiamu)</label>
                                <input type="password" name="password_baru" class="form-control" required placeholder="Minimal 6 karakter">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Ketik Ulang Password Baru</label>
                                <input type="password" name="password_baru_confirmation" class="form-control" required placeholder="Ketik ulang password baru">
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light">
                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-fintrack rounded-pill px-4 fw-bold">Simpan Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>