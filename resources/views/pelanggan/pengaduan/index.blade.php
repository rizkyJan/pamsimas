@extends('layout')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Riwayat Pengaduan Saya</h3>
        <a href="{{ route('pelanggan.pengaduan.create') }}" class="btn btn-primary">
            Buat Pengaduan Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Keluhan</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengaduans as $pengaduan)
                            <tr>
                                <td>{{ $pengaduan->created_at->format('d F Y') }}</td>
                                
                                <td>{{ $pengaduan->subjek }}</td>
                                
                                <td>
                                    @if ($pengaduan->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif ($pengaduan->status == 'diproses')
                                        <span class="badge bg-info text-dark">Diproses</span>
                                    @elseif ($pengaduan->status == 'dikirim')
                                        <span class="badge bg-warning text-dark">Terkirim</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $pengaduan->status }}</span> 
                                    @endif
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('pelanggan.pengaduan.show', $pengaduan->id) }}" class="btn btn-sm btn-secondary">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    Anda belum memiliki riwayat pengaduan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection