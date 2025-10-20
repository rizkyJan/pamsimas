@extends('layout')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-3">Detail Pengaduan</h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div><strong>Pelanggan:</strong> {{ $pengaduan->pelanggan->nama_pelanggan ?? 'N/A' }}</div>
                    <div><strong>Alamat:</strong> {{ $pengaduan->pelanggan->alamat ?? 'N/A' }}</div>
                </div>
                
                <div class="col-md-6 text-md-end">
                    <strong>Status:</strong> 
                    @if ($pengaduan->status == 'dikirim')
                        <span class="badge bg-warning text-dark">Terkirim</span>
                    @elseif ($pengaduan->status == 'diproses')
                        <span class="badge bg-info text-dark">Diproses</span>
                    @elseif ($pengaduan->status == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-secondary">{{ $pengaduan->status }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            
            <h5 class="card-title">Subjek: {{ $pengaduan->subjek }}</h5>
            <h5 class="card-title mt-3">Keluhan Pelanggan:</h5>
            <p class="card-text bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $pengaduan->isi_pengaduan }}</p>

            <hr>

            <h5 class="card-title mt-4">Tanggapan Anda:</h5>
            @if ($pengaduan->tanggapan)
                <div class="alert alert-success">
                    <strong>Anda sudah menanggapi pada:</strong> {{ $pengaduan->updated_at->translatedFormat('d F Y, H:i') }}
                    <p class="mt-2 mb-0" style="white-space: pre-wrap;">{{ $pengaduan->tanggapan }}</p>
                </div>
            @else
                <form action="{{ route('admin.pengaduan.tanggapi', $pengaduan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <textarea class="form-control @error('tanggapan') is-invalid @enderror" id="tanggapan" name="tanggapan" rows="4" required placeholder="Tulis tanggapan Anda di sini..."></textarea>
                        @error('tanggapan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
                </form>
            @endif
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
        </div>
    </div>
</div>
@endsection