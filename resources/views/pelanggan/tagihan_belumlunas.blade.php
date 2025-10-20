@extends('layout')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-3">❌ Tagihan Belum Lunas</h3>
    @if ($tagihan_belum_lunas->isEmpty())
        <p class="text-muted">Tidak ada tagihan yang belum dibayar.</p>
    @else
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Bulan</th>
                    <th>Pemakaian (m³)</th>
                    <th>Total (Rp)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tagihan_belum_lunas as $tagihan)
                <tr>
                    <td>{{ $tagihan->bulan->nama_bulan_tahun ?? '-' }}</td>
                    <td>{{ $tagihan->pemakaian }}</td>
                    <td>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                    <td><span class="badge bg-danger">Belum Bayar</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
