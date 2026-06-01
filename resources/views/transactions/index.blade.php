@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-start align-items-md-center mb-4 gap-3 gap-md-0">
        <div>
            <h3 class="mb-0 fw-bold text-break text-fintrack">Riwayat Transaksi</h3>
            <p class="text-muted small mb-0">Kelola data pemasukan dan pengeluaran UMKM-mu.</p>
        </div>
        
        <div class="w-100 w-md-auto d-flex flex-column flex-sm-row gap-2">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary shadow-sm d-none d-md-inline-block rounded-pill">
                <i class="bi bi-arrow-left me-1"></i>Dashboard
            </a>
            
            <button type="button" class="btn btn-outline-danger shadow-sm w-100 w-sm-auto text-nowrap rounded-pill" data-bs-toggle="modal" data-bs-target="#modalEksporPDF">
                <i class="bi bi-file-earmark-pdf me-1"></i> Ekspor PDF
            </button>

            <button type="button" class="btn btn-fintrack shadow-sm w-100 w-sm-auto text-nowrap rounded-pill" data-bs-toggle="modal" data-bs-target="#modalInputTransaksi">
                <i class="bi bi-plus-circle me-1"></i> Catat Transaksi
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm card-hover">
        <div class="card-body p-3 p-md-4">
            <h5 class="fw-bold mb-3 text-dark d-none d-md-block">Daftar Transaksi Tersimpan</h5>
            
            <div class="row g-3">
                @forelse($transactions as $trx)
                    <div class="col-12">
                        <div class="p-3 border rounded shadow-sm bg-white">
                            <div class="row align-items-center">
                                <div class="col-6 col-md-2 mb-2 mb-md-0">
                                    <div class="fw-medium text-secondary small text-nowrap">
                                        {{ \Carbon\Carbon::parse($trx->transaction_date)->translatedFormat('d M Y') }}
                                    </div>
                                </div>

                                <div class="col-6 col-md-2 mb-2 mb-md-0 text-md-center">
                                    @if($trx->type == 'pemasukan')
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 text-nowrap">Pemasukan</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2 text-nowrap">Pengeluaran</span>
                                    @endif
                                </div>

                                <div class="col-12 col-md-3 mb-2 mb-md-0 text-dark">
                                    <div class="fw-bold d-inline d-md-none small text-muted me-1">Kategori:</div>
                                    <span class="fw-medium">{{ $trx->category }}</span>
                                </div>

                                <div class="col-12 col-md-2 mb-3 mb-md-0 fw-bold fs-5 {{ $trx->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </div>
                                
                                <div class="col-12 col-md-3 text-md-end d-flex gap-1 justify-content-start justify-content-md-end">
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $trx->id }}">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $trx->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-5 border rounded bg-light">
                            <i class="bi bi-journal-x fs-1 d-block mb-3 text-black-50"></i>
                            Belum ada transaksi yang dicatat. Kuy catat dulu!
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@foreach($transactions as $trx)
    <div class="modal fade" id="modalDetail{{ $trx->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header {{ $trx->type == 'pemasukan' ? 'bg-success text-white' : 'bg-danger text-white' }} border-bottom-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi {{ $trx->type == 'pemasukan' ? 'bi-arrow-down-circle' : 'bi-arrow-up-circle' }} me-2"></i> Detail {{ ucfirst($trx->type) }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 border-bottom pb-2">
                        <small class="text-muted d-block">Tanggal Transaksi</small>
                        <span class="fw-medium">{{ \Carbon\Carbon::parse($trx->transaction_date)->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="mb-3 border-bottom pb-2">
                        <small class="text-muted d-block">Kategori Utama</small>
                        <span class="fw-medium">{{ $trx->category }}</span>
                    </div>
                    <div class="mb-3 border-bottom pb-2">
                        <small class="text-muted d-block">Nominal</small>
                        <span class="fw-bold fs-5 {{ $trx->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Informasi Catatan</small>
                        <div class="bg-light p-3 rounded text-dark text-break">
                            {{ $trx->description ? $trx->description : 'Tidak ada catatan tambahan untuk transaksi ini.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit{{ $trx->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark border-bottom-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Ubah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('transactions.update', $trx->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-medium text-muted small mb-2">Kategori</label>
                                <select name="category" class="form-select bg-light" required>
                                    @if($trx->type == 'pemasukan')
                                        <option value="Penjualan" {{ $trx->category == 'Penjualan' ? 'selected' : '' }}>Penjualan</option>
                                        <option value="Suntikan Modal" {{ $trx->category == 'Suntikan Modal' ? 'selected' : '' }}>Suntikan Modal</option>
                                        <option value="Lain-lain" {{ $trx->category == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                    @else
                                        <option value="Bahan Baku" {{ $trx->category == 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku</option>
                                        <option value="Alat Masak / Kemasan" {{ $trx->category == 'Alat Masak / Kemasan' ? 'selected' : '' }}>Alat Masak / Kemasan</option>
                                        <option value="Gaji Pegawai" {{ $trx->category == 'Gaji Pegawai' ? 'selected' : '' }}>Gaji Pegawai</option>
                                        <option value="Operasional (Listrik/Air)" {{ $trx->category == 'Operasional (Listrik/Air)' ? 'selected' : '' }}>Operasional (Listrik/Air)</option>
                                        <option value="Sewa Tempat" {{ $trx->category == 'Sewa Tempat' ? 'selected' : '' }}>Sewa Tempat</option>
                                        <option value="Lain-lain" {{ $trx->category == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-medium text-muted small mb-2">Nominal (Rp)</label>
                                <input type="number" name="amount" class="form-control bg-light" value="{{ $trx->amount }}" required min="1">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-muted small mb-2">Tanggal</label>
                            <input type="date" name="transaction_date" class="form-control bg-light" value="{{ $trx->transaction_date }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium text-muted small mb-2">Keterangan / Info Barang</label>
                            <textarea name="description" class="form-control bg-light" rows="3">{{ $trx->description }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 shadow-sm py-2 fw-bold text-dark rounded-pill">
                            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="modalInputTransaksi" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-fintrack text-white border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalLabel">Catat Transaksi Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small mb-2">Jenis Transaksi</label>
                        <div class="btn-group w-100 shadow-sm" role="group">
                            <input type="radio" class="btn-check" name="type" id="pemasukan" value="pemasukan" checked required>
                            <label class="btn btn-outline-success" for="pemasukan"><i class="bi bi-box-arrow-in-down me-1"></i>Masuk</label>
                            <input type="radio" class="btn-check" name="type" id="pengeluaran" value="pengeluaran" required>
                            <label class="btn btn-outline-danger" for="pengeluaran"><i class="bi bi-box-arrow-up me-1"></i>Keluar</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-medium text-muted small mb-2">Kategori</label>
                            <select name="category" id="kategoriPilihan" class="form-select bg-light" required></select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-medium text-muted small mb-2">Nominal (Rp)</label>
                            <input type="number" name="amount" class="form-control bg-light" placeholder="15000" required min="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small mb-2">Tanggal</label>
                        <input type="date" name="transaction_date" class="form-control bg-light" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small mb-2">Keterangan / Info Barang (Opsional)</label>
                        <textarea name="description" class="form-control bg-light" rows="3" placeholder="Cth: Penjualan 5 porsi nasi goreng..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-fintrack w-100 shadow-sm py-2 fw-bold rounded-pill">
                        <i class="bi bi-save me-1"></i>Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEksporPDF" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-pdf me-2"></i> Ekspor Laporan PDF</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('transactions.export') }}" method="GET">
                    <p class="text-muted small mb-3">Pilih rentang waktu untuk laporan keuangan yang ingin dicetak.</p>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-medium text-muted small mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control bg-light" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-medium text-muted small mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control bg-light" required value="{{ date('Y-m-t') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 shadow-sm py-2 fw-bold rounded-pill">
                        <i class="bi bi-download me-1"></i> Download PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioPemasukan = document.getElementById('pemasukan');
        const radioPengeluaran = document.getElementById('pengeluaran');
        const kategoriSelect = document.getElementById('kategoriPilihan');

        const opsiPemasukan = ['Penjualan', 'Suntikan Modal', 'Lain-lain'];
        const opsiPengeluaran = ['Bahan Baku', 'Alat Masak / Kemasan', 'Gaji Pegawai', 'Operasional (Listrik/Air)', 'Sewa Tempat', 'Lain-lain'];

        function updateKategori() {
            kategoriSelect.innerHTML = '';
            let opsiYangDipakai = radioPemasukan.checked ? opsiPemasukan : opsiPengeluaran;
            opsiYangDipakai.forEach(function(kategori) {
                let elemenOption = document.createElement('option');
                elemenOption.value = kategori;
                elemenOption.textContent = kategori;
                kategoriSelect.appendChild(elemenOption);
            });
        }

        radioPemasukan.addEventListener('change', updateKategori);
        radioPengeluaran.addEventListener('change', updateKategori);
        updateKategori();
    });
</script>
@endsection