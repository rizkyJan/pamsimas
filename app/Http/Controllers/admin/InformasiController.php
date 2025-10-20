<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function edit()
    {
        $informasi = Informasi::first();
        return view('admin.informasi.edit', compact('informasi'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $informasi = Informasi::first();
        $informasi->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        return redirect()->route('informasi.edit')->with('success', 'Informasi berhasil diperbarui!');
    }
}
