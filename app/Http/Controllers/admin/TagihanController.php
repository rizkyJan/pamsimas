<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Bulan;
use App\Models\Tarif;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihans = Tagihan::with(['pelanggan', 'bulan', 'tarif'])->get();

        // Hitung denda otomatis
        foreach ($tagihans as $t) {
            $t->denda = 0;
            if (Carbon::now()->gt(Carbon::parse($t->tanggal_jatuh_tempo)) && $t->status == 'belum bayar') {
                $t->denda = $t->tarif->biaya_denda ?? 0;
            }
            $t->total_dengan_denda = $t->total_tagihan + $t->denda;
        }

        return view('admin.tagihan.index', compact('tagihans'));
    }

    public function create()
    {
        // ✅ Hanya ambil pelanggan yang:
        // 1. status-nya 'aktif'
        // 2. user-nya punya role 'pelanggan'
        $pelanggans = Pelanggan::where('status', 'aktif')
            ->whereHas('user', function ($query) {
                $query->where('role', 'pelanggan');
            })
            ->get();

        $bulans = Bulan::all();
        $tarifs = Tarif::all();

        return view('admin.tagihan.create', compact('pelanggans', 'bulans', 'tarifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'bulan_id' => 'required|exists:bulans,id',
            'tarif_id' => 'required|exists:tarifs,id',
            'meter_akhir' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);

        $cekTagihan = Tagihan::where('pelanggan_id', $request->pelanggan_id)
            ->where('bulan_id', $request->bulan_id)
            ->first();

        if ($cekTagihan) {
            return redirect()->back()->with('error', 'Tagihan untuk pelanggan ini pada bulan tersebut sudah ada.');
        }

        $lastTagihan = Tagihan::where('pelanggan_id', $request->pelanggan_id)
            ->orderBy('id', 'desc')
            ->first();

        $meter_awal = $lastTagihan ? $lastTagihan->meter_akhir : 0;
        $pemakaian = $request->meter_akhir - $meter_awal;

        if ($pemakaian < 0) {
            return redirect()->back()->with('error', 'Meter akhir tidak boleh lebih kecil dari meter awal.');
        }

        $tarif = Tarif::find($request->tarif_id);
        $total_tagihan = ($tarif->biaya_beban ?? 0) + ($pemakaian * $tarif->harga_per_m3);

        Tagihan::create([
            'pelanggan_id' => $request->pelanggan_id,
            'bulan_id' => $request->bulan_id,
            'tarif_id' => $request->tarif_id,
            'meter_awal' => $meter_awal,
            'meter_akhir' => $request->meter_akhir,
            'pemakaian' => $pemakaian,
            'total_tagihan' => $total_tagihan,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status' => 'belum bayar',
        ]);

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dibuat.');
    }

    public function show(Tagihan $tagihan)
    {
        $tagihan->denda = 0;
        if (Carbon::now()->gt(Carbon::parse($tagihan->tanggal_jatuh_tempo)) && $tagihan->status == 'belum bayar') {
            $tagihan->denda = $tagihan->tarif->biaya_denda ?? 0;
        }
        $tagihan->total_dengan_denda = $tagihan->total_tagihan + $tagihan->denda;

        return view('admin.tagihan.show', compact('tagihan'));
    }

    public function edit(Tagihan $tagihan)
    {
        $pelanggans = Pelanggan::where('status', 'aktif')
            ->whereHas('user', function ($query) {
                $query->where('role', 'pelanggan');
            })
            ->get();

        $bulans = Bulan::all();
        $tarifs = Tarif::all();

        return view('admin.tagihan.edit', compact('tagihan', 'pelanggans', 'bulans', 'tarifs'));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $tarif = Tarif::find($request->tarif_id);
        $total = $tarif->biaya_beban + ($request->pemakaian * $tarif->harga_per_m3);

        $tagihan->update([
            'pelanggan_id' => $request->pelanggan_id,
            'bulan_id' => $request->bulan_id,
            'tarif_id' => $request->tarif_id,
            'pemakaian' => $request->pemakaian,
            'total_tagihan' => $total,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
        ]);

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dihapus.');
    }

    public function bayar(Tagihan $tagihan)
    {
        $tarif = $tagihan->tarif;
        $denda = 0;
        if (Carbon::now()->gt(Carbon::parse($tagihan->tanggal_jatuh_tempo)) && $tagihan->status == 'belum bayar') {
            $denda = $tarif->biaya_denda ?? 0;
        }

        $total = $tagihan->total_tagihan + $denda;
        return view('admin.tagihan.bayar', compact('tagihan', 'denda', 'total'));
    }

    public function prosesBayar(Request $request, Tagihan $tagihan)
    {
        $request->validate(['jumlah_bayar' => 'required|numeric|min:0']);
        $tarif = $tagihan->tarif;

        $denda = 0;
        if (Carbon::now()->gt(Carbon::parse($tagihan->tanggal_jatuh_tempo)) && $tagihan->status == 'belum bayar') {
            $denda = $tarif->biaya_denda ?? 0;
        }

        $total = $tagihan->total_tagihan + $denda;
        $kembalian = $request->jumlah_bayar - $total;

        if ($request->jumlah_bayar >= $total) {
            $tagihan->update(['status' => 'sudah bayar']);
        }

        return view('admin.tagihan.hasilBayar', compact('tagihan', 'total', 'denda', 'kembalian'));
    }

    public function getDataPelanggan($id)
    {
        $lastTagihan = Tagihan::where('pelanggan_id', $id)->orderBy('id', 'desc')->first();
        $meterAwal = $lastTagihan ? $lastTagihan->meter_akhir : 0;

        $semuaBulan = Bulan::orderBy('id', 'asc')->get();
        $bulanSudahAda = Tagihan::where('pelanggan_id', $id)->pluck('bulan_id')->toArray();

        $bulanTersedia = $semuaBulan->filter(fn($b) => !in_array($b->id, $bulanSudahAda))->values();

        return response()->json([
            'meter_awal' => $meterAwal,
            'bulanTersedia' => $bulanTersedia->map(fn($b) => [
                'id' => $b->id,
                'nama_bulan_tahun' => $b->nama_bulan_tahun
            ])
        ]);
    }
}
