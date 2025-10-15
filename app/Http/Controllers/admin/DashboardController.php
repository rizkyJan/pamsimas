<?php

namespace App\Http\Controllers\admin;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = Pelanggan::count();
        $totalTagihan = Tagihan::count();
        $sudahBayar = Tagihan::where('status', 'sudah bayar')->count();
        $belumBayar = Tagihan::where('status', 'belum bayar')->count();
        $totalPendapatan = Tagihan::where('status', 'sudah bayar')->sum('total_tagihan');

        // Data untuk grafik
        $grafik = Tagihan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total_tagihan) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        $bulan = $grafik->pluck('bulan');
        $total = $grafik->pluck('total');

        // 5 tagihan terakhir
        $tagihanTerbaru = Tagihan::with('pelanggan', 'bulan')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPelanggan', 'totalTagihan', 'sudahBayar', 'belumBayar',
            'totalPendapatan', 'bulan', 'total', 'tagihanTerbaru'
        ));
    }
}
