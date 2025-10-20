@extends('layout')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-3">Data Tagihan</h2>
    
    {{-- PERBAIKAN: Menggunakan nama route yang benar dengan prefix 'admin.' --}}
    <a href="{{ route('admin.tagihan.create') }}" class="btn btn-primary mb-3">Tambah Tagihan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pelanggan</th>
                        <th>Bulan</th>
                        <th>Pemakaian (m³)</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tagihans as $tagihan)
                        <tr>
                            <td>{{ $tagihan->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                            <td>{{ $tagihan->bulan->nama_bulan_tahun ?? 'N/A' }}</td>
                            <td>{{ $tagihan->pemakaian }}</td>
                            <td>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                            <td>
                                @if ($tagihan->status == 'belum bayar')
                                    <span class="badge bg-warning text-dark">Belum Bayar</span>
                                @else
                                    <span class="badge bg-success">Sudah Bayar</span>
                                @endif
                            </td>
                            <td class="text-end">
                                {{-- PERBAIKAN: Menambahkan prefix 'admin.' ke semua route --}}
                                <a href="{{ route('admin.tagihan.show', $tagihan->id) }}" class="btn btn-sm btn-secondary">Detail</a>
                                <a href="{{ route('admin.tagihan.edit', $tagihan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                
                                @if ($tagihan->status == 'belum bayar')
                                <a href="{{ route('admin.tagihan.bayar', $tagihan->id) }}" class="btn btn-sm btn-success">Bayar</a>
                                @endif

                                <form action="{{ route('admin.tagihan.destroy', $tagihan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

