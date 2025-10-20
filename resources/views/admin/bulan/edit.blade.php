@extends('layout')

@section('content')
<div class="container">
    <h2>Edit Data Bulan</h2>
    <form action="{{ route('admin.bulan.update', $bulan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Bulan-Tahun</label>
            <input type="text" name="nama_bulan_tahun" value="{{ $bulan->nama_bulan_tahun }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Bulan</label>
            <input type="number" name="bulan" value="{{ $bulan->bulan }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tahun</label>
            <input type="number" name="tahun" value="{{ $bulan->tahun }}" class="form-control" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.bulan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
