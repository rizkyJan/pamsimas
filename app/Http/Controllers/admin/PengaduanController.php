<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    /**
     * Menampilkan daftar semua pengaduan yang masuk.
     */
    public function index()
    {
        $pengaduans = Pengaduan::with('pelanggan') // Mengambil data pelanggan terkait untuk efisiensi
            ->orderByDesc('created_at') // Urutkan dari yang terbaru
            ->get();
            
        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    /**
     * Menampilkan detail satu pengaduan untuk dilihat atau ditanggapi.
     */
    public function show(Pengaduan $pengaduan)
    {
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Menyimpan tanggapan dari admin dan mengubah status pengaduan.
     */
    public function tanggapi(Request $request, Pengaduan $pengaduan)
    {
        // Validasi input, pastikan tanggapan tidak kosong
        $request->validate([
            'tanggapan' => 'required|string',
        ]);

        // Update data pengaduan di database
        $pengaduan->update([
            'tanggapan' => $request->tanggapan,
            'status' => 'diproses', // Status otomatis berubah setelah ditanggapi
        ]);

        // Kembali ke halaman detail dengan pesan sukses
        return redirect()->route('admin.pengaduan.show', $pengaduan)->with('success', 'Tanggapan berhasil dikirim.');
    }
}
