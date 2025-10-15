<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PelangganDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        // Ambil semua tagihan pelanggan
        $riwayat_tagihan = $pelanggan->tagihans()->with('bulan')->orderByDesc('id')->get();

        // Total tagihan yang belum dibayar
        $total_tagihan_belum = $riwayat_tagihan
            ->where('status', 'belum bayar')
            ->sum('total_tagihan');

        // Jumlah tagihan yang belum dibayar
        $jumlah_belum_bayar = $riwayat_tagihan
            ->where('status', 'belum bayar')
            ->count();

        // Pemakaian terakhir
        $tagihan_terakhir = $riwayat_tagihan->first();
        $pemakaian_akhir = $tagihan_terakhir ? $tagihan_terakhir->meter_akhir : 0;

        // Tentukan status dan warna
        if ($jumlah_belum_bayar === 0) {
            $status_text = 'Lunas semua';
            $status_color = 'success'; // hijau
        } else {
            $status_text = "{$jumlah_belum_bayar} belum dibayar";
            $status_color = 'warning'; // kuning
        }

        return view('pelanggan.dashboard', [
            'user' => $user,
            'pelanggan' => $pelanggan,
            'riwayat_tagihan' => $riwayat_tagihan,
            'total_tagihan' => $total_tagihan_belum,
            'pemakaian' => $pemakaian_akhir,
            'status_text' => $status_text,
            'status_color' => $status_color,
        ]);
    }
}
