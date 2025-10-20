@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold mb-4">Edit Informasi Dashboard Pelanggan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('informasi.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Informasi</label>
            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                   name="judul" value="{{ old('judul', $informasi->judul) }}">
            @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="isi" class="form-label">Isi Informasi</label>
            <textarea name="isi" rows="5" class="form-control @error('isi') is-invalid @enderror">{{ old('isi', $informasi->isi) }}</textarea>
            @error('isi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
