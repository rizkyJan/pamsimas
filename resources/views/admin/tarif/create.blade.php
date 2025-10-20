@extends('layout')

@section('content')
<div class="container">
    <h2>Tambah Tarif Baru</h2>
    <form action="{{ route('admin.tarif.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Tarif</label>
            <input type="text" name="nama_tarif" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Biaya Beban</label>
            <input type="number" name="biaya_beban" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Biaya Denda</label>
            <input type="number" name="biaya_denda" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Harga per m³</label>
            <input type="number" name="harga_per_m3" step="0.01" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.tarif.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
