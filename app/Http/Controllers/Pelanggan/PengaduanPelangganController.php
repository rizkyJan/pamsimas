<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanPelangganController extends Controller
{
    // Menampilkan daftar pengaduan milik pelanggan
    public function index()
    {
        $pengaduans = Pengaduan::where('pelanggan_id', Auth::user()->pelanggan->id)
            ->orderByDesc('created_at')
            ->get();
        return view('pelanggan.pengaduan.index', compact('pengaduans'));
    }

    // Menampilkan form untuk membuat pengaduan baru
    public function create()
    {
        return view('pelanggan.pengaduan.create');
    }

    // Menyimpan pengaduan baru
    public function store(Request $request)
    {
        $request->validate([
            'subjek' => 'required|string|max:255',
            'isi_pengaduan' => 'required|string',
        ]);

        Pengaduan::create([
            'pelanggan_id' => Auth::user()->pelanggan->id,
            'subjek' => $request->subjek,
            'isi_pengaduan' => $request->isi_pengaduan,
        ]);

        return redirect()->route('pelanggan.pengaduan.index')->with('success', 'Pengaduan berhasil dikirim.');
    }

    // Menampilkan detail satu pengaduan
    public function show(Pengaduan $pengaduan)
    {
        // Keamanan: pastikan pelanggan hanya bisa melihat pengaduannya sendiri
        if ($pengaduan->pelanggan_id !== Auth::user()->pelanggan->id) {
            abort(403);
        }
        return view('pelanggan.pengaduan.show', compact('pengaduan'));
    }

    // Menandai pengaduan sebagai selesai oleh pelanggan
    public function selesaikan(Pengaduan $pengaduan)
    {
        if ($pengaduan->pelanggan_id !== Auth::user()->pelanggan->id) {
            abort(403);
        }
        $pengaduan->update(['status' => 'selesai']);
        return redirect()->route('pelanggan.pengaduan.show', $pengaduan)->with('success', 'Pengaduan telah ditandai sebagai selesai.');
    }
}
