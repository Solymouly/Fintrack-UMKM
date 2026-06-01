@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4 text-center">Lupa Password?</h4>
                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
                    
                    <form action="{{ route('custom.cek') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Masukkan Email UMKM Anda:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Cek Status / Minta Reset</button>
                        <a href="{{ route('login') }}" class="btn btn-link w-100 mt-2 text-decoration-none">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection