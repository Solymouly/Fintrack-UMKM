<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    // Nampilin halaman utama transaksi beserta data yang udah disimpen
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
                                   ->orderBy('transaction_date', 'desc')
                                   ->get();
        return view('transactions.index', compact('transactions'));
    }

    // Proses nyimpen transaksi baru (FS-04)
    // Proses nyimpen transaksi baru (FS-04)
    public function store(Request $request)
    {
        // Validasi biar datanya nggak ngaco
        $validated = $request->validate([
            'type' => 'required|in:pemasukan,pengeluaran',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string' // <-- INI YANG BARU DITAMBAHIN
        ]);

        // Masukin ID user yang lagi login
        $validated['user_id'] = Auth::id();
        
        // Simpan ke database
        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Mantap! Transaksi dan detailnya berhasil dicatat.');
    }

    // Proses hapus transaksi (FS-06)
    public function destroy($id)
    {
        $transaction = Transaction::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    // Fungsi untuk nge-generate dan download PDF (FS-08)
// Fungsi untuk nge-generate dan download PDF (FS-08)
    public function exportPdf(Request $request)
    {
        $userId = Auth::id();
        $query = Transaction::where('user_id', $userId);

        // Kalau usernya milih rentang tanggal, kita filter datanya
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->orderBy('transaction_date', 'asc')->get();

        $pemasukan = $transactions->where('type', 'pemasukan')->sum('amount');
        $pengeluaran = $transactions->where('type', 'pengeluaran')->sum('amount');
        $saldo = $pemasukan - $pengeluaran;

        // Bikin variabel string buat nampilin periode di kertas PDF-nya
        $periode = 'Semua Waktu';
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $periode = \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($request->end_date)->format('d/m/Y');
        }

        $pdf = Pdf::loadView('transactions.pdf', compact('transactions', 'pemasukan', 'pengeluaran', 'saldo', 'periode'));
        
        return $pdf->download('Laporan_Keuangan_UMKM.pdf');
    }


    // Fungsi Update untuk memproses form Edit Transaksi (FS-05)
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Sip! Data transaksi berhasil diperbarui.');
    }
}