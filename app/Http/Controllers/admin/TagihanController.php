<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Bulan;
use App\Models\Tarif;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Http\Controllers\Controller;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tagihans = Tagihan::with(['pelanggan', 'bulan', 'tarif'])->get();

        // Menghitung denda secara dinamis untuk ditampilkan di view
        foreach ($tagihans as $t) {
            $t->denda = 0;
            if (Carbon::now()->gt(Carbon::parse($t->tanggal_jatuh_tempo)) && $t->status == 'belum bayar') {
                $t->denda = $t->tarif->biaya_denda ?? 0;
            }
            $t->total_dengan_denda = $t->total_tagihan + $t->denda;
        }

        return view('admin.tagihan.index', compact('tagihans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelanggans = Pelanggan::where('status', 'aktif')
            ->whereHas('user', function ($query) {
                $query->where('role', 'pelanggan');
            })
            ->get();

        $bulans = Bulan::all();
        $tarifs = Tarif::all();

        return view('admin.tagihan.create', compact('pelanggans', 'bulans', 'tarifs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required',
            'bulan_id' => 'required',
            'tarif_id' => 'required',
            'meter_awal' => 'required|numeric',
            'meter_akhir' => 'required|numeric|gte:meter_awal',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);

        $tarif = Tarif::findOrFail($request->tarif_id);
        $pemakaian = $request->meter_akhir - $request->meter_awal;

        // Menggunakan 'harga_per_m3' sesuai nama kolom database
        $total_tagihan = ($pemakaian * $tarif->harga_per_m3) + $tarif->biaya_beban;

        Tagihan::create([
            'pelanggan_id' => $request->pelanggan_id,
            'bulan_id' => $request->bulan_id,
            'tarif_id' => $request->tarif_id,
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
            'pemakaian' => $pemakaian,
            'total_tagihan' => $total_tagihan,
            'denda' => 0,
            'total_bayar' => $total_tagihan,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status' => 'belum bayar'
        ]);

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tagihan $tagihan)
    {
        $tagihan->denda = 0;
        if (Carbon::now()->gt(Carbon::parse($tagihan->tanggal_jatuh_tempo)) && $tagihan->status == 'belum bayar') {
            $tagihan->denda = $tagihan->tarif->biaya_denda ?? 0;
        }
        $tagihan->total_dengan_denda = $tagihan->total_tagihan + $tagihan->denda;

        return view('admin.tagihan.show', compact('tagihan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'pelanggan_id' => 'required',
            'bulan_id' => 'required',
            'tarif_id' => 'required',
            'meter_awal' => 'required|numeric',
            'meter_akhir' => 'required|numeric|gte:meter_awal',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);

        $tarif = Tarif::findOrFail($request->tarif_id);
        $pemakaian = $request->meter_akhir - $request->meter_awal;

        // Menggunakan 'harga_per_m3' sesuai nama kolom database
        $total_tagihan = ($pemakaian * $tarif->harga_per_m3) + $tarif->biaya_beban;

        $tagihan->update([
            'pelanggan_id' => $request->pelanggan_id,
            'bulan_id' => $request->bulan_id,
            'tarif_id' => $request->tarif_id,
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
            'pemakaian' => $pemakaian,
            'total_tagihan' => $total_tagihan,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
        ]);

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dihapus.');
    }

    /**
     * Show the payment form.
     */
    public function bayar(Tagihan $tagihan)
    {
        $tarif = $tagihan->tarif;
        $denda = 0;
        if (Carbon::now()->gt(Carbon::parse($tagihan->tanggal_jatuh_tempo)) && $tagihan->status == 'belum bayar') {
            $denda = $tarif->biaya_denda ?? 0;
        }

        $total = $tagihan->total_tagihan + $denda;

        // Simpan denda sementara ke database untuk ditampilkan di form
        $tagihan->update([
            'denda' => $denda,
            'total_bayar' => $total,
        ]);

        return view('admin.tagihan.bayar', compact('tagihan', 'denda', 'total'));
    }

    /**
     * Process the payment.
     */
    public function prosesBayar(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0'
        ]);

        // Hitung ulang denda untuk memastikan nilai terbaru
        $denda = 0;
        if (Carbon::now()->gt(Carbon::parse($tagihan->tanggal_jatuh_tempo)) && $tagihan->status == 'belum bayar') {
            $denda = $tagihan->tarif->biaya_denda ?? 0;
        }

        $total = $tagihan->total_tagihan + $denda;
        $kembalian = $request->jumlah_bayar - $total;

        // Update status pembayaran di database
        $tagihan->update([
            'denda' => $denda,
            'total_bayar' => $total,
            'status' => 'sudah bayar',
        ]);

        return view('admin.tagihan.hasilBayar', compact('tagihan', 'total', 'kembalian', 'denda'));
    }
    
    /**
     * Get customer data for AJAX request.
     */
    public function getDataPelanggan($id)
    {
        $lastTagihan = Tagihan::where('pelanggan_id', $id)->orderBy('id', 'desc')->first();
        $meterAwal = $lastTagihan ? $lastTagihan->meter_akhir : 0;
        $semuaBulan = Bulan::orderBy('id', 'asc')->get();
        $bulanSudahAda = Tagihan::where('pelanggan_id', $id)->pluck('bulan_id')->toArray();
        $bulanTersedia = $semuaBulan->filter(fn ($b) => !in_array($b->id, $bulanSudahAda))->values();
        
        return response()->json([
            'meter_awal' => $meterAwal,
            'bulanTersedia' => $bulanTersedia->map(fn ($b) => [
                'id' => $b->id,
                'nama_bulan_tahun' => $b->nama_bulan_tahun
            ])
        ]);
    }

    /**
     * Generate a PDF receipt for the specified transaction.
     */
    public function cetakBukti(Tagihan $tagihan)
    {
        // Pastikan semua data relasi yang dibutuhkan sudah ter-load
        $tagihan->load('pelanggan', 'bulan', 'tarif');

        // Data tambahan yang mungkin tidak ada di model tagihan
        $data = [
            'tagihan' => $tagihan,
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y'),
        ];
        
        // Membuat PDF dari view 'bukti-pembayaran-pdf.blade.php'
        $pdf = PDF::loadView('admin.tagihan.bukti-pembayaran-pdf', $data);

        // Menampilkan PDF di browser
        return $pdf->stream('bukti-pembayaran-'. $tagihan->pelanggan->nama_pelanggan .'-'. $tagihan->bulan->nama_bulan_tahun .'.pdf');
    }
}
