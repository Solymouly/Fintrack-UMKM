<!DOCTYPE html>
<html>
<head>
    <title>Rekap Harian FinTrack</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo, {{ $user->name }}! 👋</h2>
    <p>Ini dia ringkasan catatan keuangan UMKM kamu untuk <strong>hari ini</strong>:</p>
    
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <p style="margin: 5px 0;">🟢 <strong>Total Pemasukan:</strong> Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
        <p style="margin: 5px 0;">🔴 <strong>Total Pengeluaran:</strong> Rp {{ number_format($pengeluaran, 0, ',', '.') }}</p>
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 10px 0;">
        <p style="margin: 5px 0; font-size: 18px;">💰 <strong>Saldo Hari Ini:</strong> Rp {{ number_format($saldo, 0, ',', '.') }}</p>
    </div>

    <p>Tetap semangat catat keuanganmu ya! Kalau butuh laporan lengkap buat besok, langsung buka aplikasi FinTrack UMKM dan klik <strong>Ekspor PDF</strong>.</p>
    
    <br>
    <p>Salam sukses,<br><strong>Tim FinTrack UMKM</strong></p>
</body>
</html>