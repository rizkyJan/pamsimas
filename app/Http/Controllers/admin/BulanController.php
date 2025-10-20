<?php

namespace App\Http\Controllers\admin;

use App\Models\Bulan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BulanController extends Controller
{
    public function index()
    {
        $bulans = Bulan::all();
        return view('admin.bulan.index', compact('bulans'));
    }

    public function create()
    {
        return view('admin.bulan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bulan_tahun' => 'required',
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        Bulan::create($request->all());
        return redirect()->route('admin.bulan.index')->with('success', 'Data bulan berhasil ditambahkan.');
    }

    public function edit(Bulan $bulan)
    {
        return view('admin.bulan.edit', compact('bulan'));
    }

    public function update(Request $request, Bulan $bulan)
    {
        $request->validate([
            'nama_bulan_tahun' => 'required',
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        $bulan->update($request->all());
        return redirect()->route('admin.bulan.index')->with('success', 'Data bulan berhasil diperbarui.');
    }

    public function destroy(Bulan $bulan)
    {
        $bulan->delete();
        return redirect()->route('admin.bulan.index')->with('success', 'Data bulan berhasil dihapus.');
    }
}
