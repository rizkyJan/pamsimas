<?php

namespace App\Http\Controllers\admin;

use App\Models\Tarif;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TarifController extends Controller
{
    public function index()
    {
        $tarifs = Tarif::all();
        return view('admin.tarif.index', compact('tarifs'));
    }

    public function create()
    {
        return view('admin.tarif.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tarif' => 'required',
            'biaya_beban' => 'required|numeric',
            'biaya_denda' => 'required|numeric',
            'harga_per_m3' => 'required|numeric',
        ]);

        Tarif::create($request->all());
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function edit(Tarif $tarif)
    {
        return view('tarif.edit', compact('tarif'));
    }

    public function update(Request $request, Tarif $tarif)
    {
        $request->validate([
            'nama_tarif' => 'required',
            'biaya_beban' => 'required|numeric',
            'biaya_denda' => 'required|numeric',
            'harga_per_m3' => 'required|numeric',
        ]);

        $tarif->update($request->all());
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    public function destroy(Tarif $tarif)
    {
        $tarif->delete();
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dihapus.');
    }
}
