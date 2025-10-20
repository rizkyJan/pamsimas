@extends('layout')

@section('content')
    <div class="container mt-4">
        <h3 class="fw-bold mb-3">✍️ Buat Pengaduan Baru</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pelanggan.pengaduan.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="subjek" class="form-label">Subjek Pengaduan:</label>
                        <input type="text" class="form-control @error('subjek') is-invalid @enderror" id="subjek"
                            name="subjek" value="{{ old('subjek') }}" required
                            placeholder="Contoh: Air tidak menyala / Kran bocor">
                        @error('subjek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isi_pengaduan" class="form-label">Jelaskan keluhan Anda:</label>
                        <textarea class="form-control @error('isi_pengaduan') is-invalid @enderror" id="isi_pengaduan" name="isi_pengaduan"
                            rows="5" required placeholder="Contoh: Air di rumah saya sudah 3 hari tidak mengalir dengan lancar.">{{ old('isi_pengaduan') }}</textarea>
                        @error('isi_pengaduan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('pelanggan.pengaduan.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Kirim Pengaduan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
