<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan UMKM</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; }
        .summary td { padding: 5px; }
        .table-data { width: 100%; border-collapse: collapse; }
        .table-data th, .table-data td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table-data th { background-color: #f2f2f2; }
        .text-success { color: green; }
        .text-danger { color: red; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Keuangan UMKM</h2>
        <p>Periode: <strong>{{ $periode }}</strong></p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Pemasukan:</strong> Rp {{ number_format($pemasukan, 0, ',', '.') }}</td>
                <td class="text-right"><strong>Total Pengeluaran:</strong> Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 16px; padding-top:10px;">
                    <strong>Saldo Akhir:</strong> Rp {{ number_format($saldo, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $trx)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->transaction_date)->format('d/m/Y') }}</td>
                <td>{{ $trx->category }}</td>
                <td>{{ ucfirst($trx->type) }}</td>
                <td class="{{ $trx->type == 'pemasukan' ? 'text-success' : 'text-danger' }}">
                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>