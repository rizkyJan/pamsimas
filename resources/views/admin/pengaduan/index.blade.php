@extends('layout')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-3">Manajemen Pengaduan Pelanggan</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengaduans as $pengaduan)
                            <tr>
                                <td>{{ $pengaduan->created_at->translatedFormat('d F Y') }}</td>
                                <td>{{ $pengaduan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                <td>{{ Str::limit($pengaduan->isi_pengaduan, 50) }}</td>
                                
                                <td>
                                    @if ($pengaduan->status == 'dikirim')
                                        <span class="badge bg-warning text-dark">Terkirim</span>
                                    @elseif ($pengaduan->status == 'diproses')
                                        <span class="badge bg-info text-dark">Diproses</span>
                                    @elseif ($pengaduan->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $pengaduan->status }}</span>
                                    @endif </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.pengaduan.show', $pengaduan->id) }}" class="btn btn-sm btn-secondary">Lihat & Tanggapi</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Tidak ada pengaduan yang masuk.
                                </td>
                            </tr>
                        @endforelse </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection