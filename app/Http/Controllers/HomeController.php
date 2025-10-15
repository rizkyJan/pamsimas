<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $totalPelanggan = Pelanggan::count();
        $totalTagihan = Tagihan::count();
        $totalBelumBayar = Tagihan::where('status', 'unpaid')->count();
        $totalPendapatan = Tagihan::where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->sum('total');

        return view('dashboard', compact('totalPelanggan', 'totalTagihan', 'totalBelumBayar', 'totalPendapatan'));
    }
}
