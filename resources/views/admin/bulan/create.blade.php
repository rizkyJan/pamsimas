@extends('layout')

@section('content')
<div class="container">
    <h2>Tambah Bulan Baru</h2>
    <form action="{{ route('bulan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Bulan-Tahun</label>
            <input type="text" name="nama_bulan_tahun" class="form-control" placeholder="Contoh: Januari 2025" required>
        </div>

        <div class="mb-3">
            <label>Bulan (1-12)</label>
            <input type="number" name="bulan" min="1" max="12" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tahun</label>
            <input type="number" name="tahun" min="2020" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('bulan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
