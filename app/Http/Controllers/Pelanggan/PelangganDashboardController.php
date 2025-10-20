<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Informasi;
use App\Models\Tagihan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PelangganDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        $riwayat_tagihan = $pelanggan->tagihans()->with('bulan')->orderByDesc('id')->get();

        $total_tagihan = $riwayat_tagihan
            ->where('status', 'belum bayar')
            ->sum('total_tagihan');

        $jumlah_belum_bayar = $riwayat_tagihan
            ->where('status', 'belum bayar')
            ->count();

        $tagihan_terakhir = $riwayat_tagihan->first();
        $pemakaian_akhir = $tagihan_terakhir ? $tagihan_terakhir->meter_akhir : 0;

        $status_text = $jumlah_belum_bayar === 0 ? 'Lunas semua' : "{$jumlah_belum_bayar} belum dibayar";
        $status_color = $jumlah_belum_bayar === 0 ? 'success' : 'warning';

        $informasi = Informasi::first();

        return view('pelanggan.dashboard', compact(
            'user', 'pelanggan', 'riwayat_tagihan',
            'total_tagihan', 'pemakaian_akhir',
            'status_text', 'status_color', 'informasi'
        ));
    }

    // Menampilkan daftar tagihan lunas
    public function tagihanLunas()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        $tagihan_lunas = $pelanggan->tagihans()
            ->where('status', 'sudah bayar')
            ->with('bulan')
            ->orderByDesc('id')
            ->get();

        return view('pelanggan.tagihan_lunas', compact('tagihan_lunas', 'user', 'pelanggan'));
    }

    // Menampilkan daftar tagihan belum lunas
    public function tagihanBelumLunas()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        $tagihan_belum_lunas = $pelanggan->tagihans()
            ->where('status', 'belum bayar')
            ->with('bulan')
            ->orderByDesc('id')
            ->get();

        return view('pelanggan.tagihan_belumlunas', compact('tagihan_belum_lunas', 'user', 'pelanggan'));
    }

    /**
     * Method baru untuk mencetak bukti pembayaran untuk pelanggan.
     *
     * @param  \App\Models\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function cetakTagihan(Tagihan $tagihan)
    {
        // Keamanan: Pastikan tagihan ini milik pelanggan yang sedang login
        if (Auth::user()->pelanggan->id !== $tagihan->pelanggan_id) {
            // Jika bukan, tolak akses
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        // Siapkan data untuk dikirim ke view PDF
        $data = [
            'tagihan' => $tagihan,
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y'),
        ];

        // Kita bisa menggunakan view PDF yang sama dengan admin
        $pdf = Pdf::loadView('pelanggan.bukti-pembayaran-pdf', $data);

        // Menampilkan PDF di browser
        return $pdf->stream('bukti-pembayaran-'. $tagihan->pelanggan->nama_pelanggan .'-'. $tagihan->bulan->nama_bulan_tahun .'.pdf');
    }
}

