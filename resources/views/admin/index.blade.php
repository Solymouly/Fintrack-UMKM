@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Area Notifikasi Hijau/Merah -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="fw-bold text-danger mb-4"><i class="bi bi-shield-lock-fill me-2"></i>Panel Kontrol Admin</h2>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="bi bi-people-fill text-primary me-2"></i>Manajemen Pengguna UMKM</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pemilik</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Aksi Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-medium">{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->is_admin)
                                                <span class="badge bg-danger rounded-pill">Super Admin</span>
                                            @else
                                                <span class="badge bg-primary rounded-pill">Pemilik UMKM</span>
                                            @endif
                                        </td>
                                        <!-- INI BAGIAN YANG BERUBAH SESUAI LANGKAH 6 DAWGG -->
                                        <td>
                                            @if(!$user->is_admin)
                                                @if($user->is_reset_requested && !$user->is_reset_approved)
                                                    <!-- Ada yang minta reset nih! -->
                                                    <form action="{{ route('admin.user.acc_reset', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning fw-bold text-dark rounded-pill px-3 shadow-sm">
                                                            <i class="bi bi-check-circle-fill"></i> ACC Lupa Password
                                                        </button>
                                                    </form>
                                                @elseif($user->is_reset_requested && $user->is_reset_approved)
                                                    <!-- Udah di-ACC, nunggu diketik sama UMKM -->
                                                    <span class="badge bg-success rounded-pill"><i class="bi bi-hourglass-split"></i> Nunggu UMKM Bikin Password</span>
                                                @else
                                                    <!-- Keadaan Normal -->
                                                    <span class="text-muted small">Aman</span>
                                                @endif
                                            @else
                                                <span class="text-muted small"><i>Super Admin</i></span>
                                            @endif
                                        </td>
                                        <!-- AKHIR BAGIAN YANG BERUBAH -->
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada pengguna yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection