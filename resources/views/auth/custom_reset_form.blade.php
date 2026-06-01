@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 border-success">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <span class="badge bg-success mb-2">Telah di-ACC Admin</span>
                        <h4 class="fw-bold">Buat Password Baru</h4>
                    </div>
                    <form action="{{ route('custom.ubah') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        
                        <div class="mb-3">
                            <label>Password Baru (Rahasia):</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label>Ketik Ulang Password Baru:</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold">Simpan & Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection