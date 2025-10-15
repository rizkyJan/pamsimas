@extends('layout')

@section('content')
    <div class="container mt-4">

        {{-- Header --}}
        <h2 class="fw-bold">Selamat Datang, {{ $user->name }} 👋</h2>
        <p class="text-muted">Status akun: <strong>{{ ucfirst($pelanggan->status ?? 'Belum terdaftar') }}</strong></p>

        {{-- Info Pelanggan --}}
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">💧 Informasi Pelanggan</h5>
                <ul class="list-unstyled mb-0">
                    <li><strong>Nama:</strong> {{ $pelanggan->nama_pelanggan }}</li>
                    <li><strong>Alamat:</strong> {{ $pelanggan->alamat }}</li>
                    <li><strong>No. HP:</strong> {{ $pelanggan->no_hp }}</li>
                    <li><strong>ID Pelanggan:</strong> {{ $pelanggan->id }}</li>
                </ul>
            </div>
        </div>

        <div class="row mt-4">
            {{-- Total Tagihan Belum Dibayar --}}
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm text-center p-3 bg-primary text-white">
                    <h6>Total Tagihan Belum Dibayar</h6>
                    <h3 class="fw-bold">Rp {{ number_format($total_tagihan, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- Pemakaian Air (meter akhir terakhir) --}}
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm text-center p-3 bg-info text-white">
                    <h6>Pemakaian Akhir Saat Ini</h6>
                    <h3 class="fw-bold">{{ $pemakaian }} m³</h3>
                </div>
            </div>

            {{-- Status Pembayaran --}}
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm text-center p-3 bg-{{ $status_color }} text-white">
                    <h6>Status Pembayaran</h6>
                    <h3 class="fw-bold">{{ $status_text }}</h3>
                </div>
            </div>




            {{-- Riwayat Tagihan --}}
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">📜 Riwayat Tagihan</h5>
                    @if ($riwayat_tagihan->isEmpty())
                        <p class="text-muted">Belum ada riwayat tagihan.</p>
                    @else
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th>Pemakaian (m³)</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat_tagihan as $tagihan)
                                    <tr>
                                        <td>{{ $tagihan->bulan->nama_bulan_tahun ?? '-' }}</td>
                                        <td>{{ $tagihan->pemakaian }}</td>
                                        <td>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                                        <td>
    @if (strtolower($tagihan->status) === 'sudah bayar')
        <span class="badge bg-success text-light px-3 py-2">sudah bayar</span>
    @else
        <span class="badge bg-danger text-light px-3 py-2">belum bayar</span>
    @endif
</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    @endsection
