@extends('layout')

@section('content')
<div class="container">
    <h2>Edit Pelanggan</h2>
    <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" value="{{ $pelanggan->nama_pelanggan }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ $pelanggan->alamat }}</textarea>
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" value="{{ $pelanggan->no_hp }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="aktif" {{ $pelanggan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $pelanggan->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
