@extends('layout')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-3">✅ Tagihan Lunas</h3>
    @if ($tagihan_lunas->isEmpty())
        <p class="text-muted">Belum ada tagihan yang sudah dibayar.</p>
    @else
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Bulan</th>
                    <th>Pemakaian (m³)</th>
                    <th>Total (Rp)</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tagihan_lunas as $tagihan)
                <tr>
                    <td>{{ $tagihan->bulan->nama_bulan_tahun ?? '-' }}</td>
                    <td>{{ $tagihan->pemakaian }}</td>
                    <td>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                    <td><span class="badge bg-success">Sudah Bayar</span></td>
                    <td class="text-end">
                        {{-- Tombol ini mengarah ke route pelanggan yang baru --}}
                        <a href="{{ route('pelanggan.cetak', $tagihan->id) }}" class="btn btn-sm btn-info" target="_blank">
                            Cetak Bukti
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
