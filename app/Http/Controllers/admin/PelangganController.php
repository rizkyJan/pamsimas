<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with('user')->paginate(10);
        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->nama_pelanggan,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan', // tambahkan kolom role di tabel users
        ]);

        // Buat data pelanggan
        Pelanggan::create([
            'user_id' => $user->id,
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $pelanggan->update($request->only(['nama_pelanggan', 'alamat', 'no_hp', 'status']));

        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan diperbarui!');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->user()->delete();
        $pelanggan->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
