@extends('layout')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-3">Detail Pengaduan</h3>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span>Dikirim pada: {{ $pengaduan->created_at->translatedFormat('d F Y, H:i') }}</span>
            <span>Status: 
                @if ($pengaduan->status == 'dikirim')
                    <span class="badge bg-warning text-dark">Terkirim</span>
                @elseif ($pengaduan->status == 'diproses')
                    <span class="badge bg-info text-dark">Sudah Ditanggapi</span>
                @else
                    <span class="badge bg-success">Selesai</span>
                @endif
            </span>
        </div>
        <div class="card-body">
            
            <h5 class="card-title">Subjek: {{ $pengaduan->subjek }}</h5>
            <h5 class="card-title mt-3">Keluhan Anda:</h5>
            <p class="card-text bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $pengaduan->isi_pengaduan }}</p>

            <hr>

            <h5 class="card-title mt-4">Tanggapan Petugas:</h5>
            @if ($pengaduan->tanggapan)
                <p class="card-text bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $pengaduan->tanggapan }}</p>

                @if ($pengaduan->status == 'diproses')
                    <div class="alert alert-info mt-4">
                        Jika Anda merasa pengaduan sudah terselesaikan, silakan klik tombol di bawah ini.
                        <form action="{{ route('pelanggan.pengaduan.selesaikan', $pengaduan->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Konfirmasi Pengaduan Selesai</button>
                        </form>
                    </div>
                @endif

            @else
                <p class="text-muted">Belum ada tanggapan dari petugas.</p>
            @endif
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('pelanggan.pengaduan.index') }}" class="btn btn-secondary">Kembali ke Riwayat</a>
        </div>
    </div>
</div>
@endsection