@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dashboard Keuangan</h2>
            <p class="text-muted mb-0">Pantau kondisi riil keuangan usaha Anda secara real-time.</p>
        </div>
        <div class="d-flex">
            <button type="button" class="btn btn-outline-info shadow-sm me-2 fw-medium" data-bs-toggle="modal" data-bs-target="#modalPanduan">
                <i class="bi bi-journal-bookmark-fill me-1"></i> Panduan
            </button>
            <a href="{{ route('transactions.index') }}" class="btn btn-fintrack shadow-sm px-4 fw-medium text-nowrap rounded-pill">
                <i class="bi bi-plus-circle me-2"></i>Catat Transaksi
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('home') }}" method="GET" class="row g-3 align-items-center m-0">
                <div class="col-auto">
                    <label class="col-form-label fw-bold text-fintrack small"><i class="bi bi-funnel-fill me-1"></i> Filter Periode:</label>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted">Dari</span>
                        <input type="date" name="start_date" class="form-control border-start-0" value="{{ $startDate }}" required>
                    </div>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted">Sampai</span>
                        <input type="date" name="end_date" class="form-control border-start-0" value="{{ $endDate }}" required>
                    </div>
                </div>
                <div class="col-12 col-md-auto d-flex gap-2">
                    <button type="submit" class="btn btn-fintrack btn-sm px-3 shadow-sm fw-medium">Terapkan</button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm px-3">Bulan Ini</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-fintrack shadow-sm text-white card-hover h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 small text-uppercase fw-bold mb-1">Saldo Berjalan</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white-50 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background: rgba(255,255,255,0.15)">
                            <i class="bi bi-wallet2 fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white card-hover h-100" style="background: linear-gradient(135deg, #1cc88a, #13855c);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 small text-uppercase fw-bold mb-1">Total Pemasukan</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($pemasukan, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white-50 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background: rgba(255,255,255,0.15)">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white card-hover h-100" style="background: linear-gradient(135deg, #e74a3b, #be2617);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-white-50 small text-uppercase fw-bold mb-1">Total Pengeluaran</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white-50 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background: rgba(255,255,255,0.15)">
                            <i class="bi bi-graph-down-arrow fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-header bg-white border-0 pt-4 fw-bold text-dark fs-5">
                    <i class="bi bi-pie-chart text-fintrack me-2"></i>Struktur Arus Kas
                </div>
                <div class="card-body d-flex align-items-center justify-content-center p-4">
                    <div style="position: relative; width: 100%; max-width: 280px;">
                        <canvas id="cashflowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark fs-5">
                        <i class="bi bi-clock-history text-fintrack me-2"></i>Transaksi Terakhir
                    </span>
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary px-3 rounded-pill">Lihat Semua</a>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-3">Tanggal</th>
                                    <th class="border-0">Kategori</th>
                                    <th class="border-0 text-end px-3">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $trx)
                                <tr>
                                    <td class="px-3 text-muted small">
                                        {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y') }}
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $trx->type == 'pemasukan' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} p-2 px-3 me-2">
                                            {{ ucfirst($trx->type) }}
                                        </span>
                                        <span class="fw-medium text-dark">{{ $trx->category }}</span>
                                    </td>
                                    <td class="text-end px-3 fw-bold {{ $trx->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                        {{ $trx->type == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-black-50"></i>
                                        Belum ada rekaman pada rentang waktu ini.
                                    </td>
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

<div class="modal fade" id="modalPanduan" tabindex="-1" aria-labelledby="modalPanduanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalPanduanLabel"><i class="bi bi-book-half me-2"></i> Cara Singkat Pakai FinTrack</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-md-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-fintrack text-white rounded-circle d-flex justify-content-center align-items-center me-3 flex-shrink-0 fw-bold fs-5" style="width: 45px; height: 45px;">1</div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Cek Kondisi Dompet</h6>
                                <p class="small text-muted mb-0">Pantau 3 kotak warna-warni di halaman ini untuk melihat total uang masuk, uang keluar, dan sisa saldo hari ini.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center me-3 flex-shrink-0 fw-bold fs-5" style="width: 45px; height: 45px;">2</div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Catat Duit (Transaksi)</h6>
                                <p class="small text-muted mb-0">Klik tombol biru <strong>"Catat Transaksi"</strong>. Pilih tombol Hijau (Masuk) atau Merah (Keluar), isi harga, dan klik Simpan. Cuma 10 detik!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-warning text-dark rounded-circle d-flex justify-content-center align-items-center me-3 flex-shrink-0 fw-bold fs-5" style="width: 45px; height: 45px;">3</div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Cek Struk (Detail)</h6>
                                <p class="small text-muted mb-0">Lupa ini transaksi apa? Di halaman Riwayat Transaksi, klik tombol <strong>"Detail"</strong> untuk baca catatan lengkapnya.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-danger text-white rounded-circle d-flex justify-content-center align-items-center me-3 flex-shrink-0 fw-bold fs-5" style="width: 45px; height: 45px;">4</div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Laporan Buat Modal</h6>
                                <p class="small text-muted mb-0">Butuh laporan untuk pinjam modal ke bank (KUR)? Klik tombol merah <strong>"Ekspor PDF"</strong> di halaman transaksi. Langsung jadi!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0 d-flex justify-content-center">
                <button type="button" class="btn btn-fintrack px-5 rounded-pill fw-medium shadow-sm" data-bs-dismiss="modal">Saya Mengerti, Mulai Pakai</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const totalPemasukan = {{ $pemasukan }};
        const totalPengeluaran = {{ $pengeluaran }};
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        
        if(totalPemasukan === 0 && totalPengeluaran === 0) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Belum Ada Data'],
                    datasets: [{ data: [1], backgroundColor: ['#eaecf4'] }]
                },
                options: { cutout: '75%', plugins: { legend: { display: false } } }
            });
            return;
        }

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [totalPemasukan, totalPengeluaran],
                    backgroundColor: ['#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#17a673', '#2c9faf'],
                    borderWidth: 4,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { family: 'sans-serif', size: 12 } } }
                }
            }
        });
    });
</script>
@endsection