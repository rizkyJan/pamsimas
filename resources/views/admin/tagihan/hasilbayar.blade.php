@extends('layout')

@section('content')
<div class="container">
    <h2>Hasil Pembayaran</h2>
    <div class="card p-4">
        <table class="table table-bordered">
            <tr>
                <th>Pelanggan</th>
                <td>{{ $tagihan->pelanggan->nama_pelanggan }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $tagihan->bulan->nama_bulan_tahun }}</td>
            </tr>
            <tr>
                <th>Pemakaian</th>
                <td>{{ $tagihan->pemakaian }} m³</td>
            </tr>
            <tr>
                <th>Tarif per m³</th>
                <td>Rp {{ number_format($tagihan->tarif->harga_per_m3, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Subtotal</th>
                <td>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Denda</th>
                <td>Rp {{ number_format($denda, 0, ',', '.') }}</td>
            </tr>
            <tr class="table-success">
                <th>Total Bayar</th>
                <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <th>Uang Dibayarkan</th>
                <td>Rp {{ number_format(request('jumlah_bayar'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Kembalian</th>
                <td>Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge bg-success">{{ ucfirst($tagihan->status) }}</span></td>
            </tr>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali ke Daftar Tagihan</a>
            {{-- Tombol ini sekarang aktif dan membuka PDF di tab baru --}}
            <a href="{{ route('tagihan.cetakBukti', $tagihan->id) }}" class="btn btn-primary" target="_blank">Cetak Bukti Pembayaran</a>
        </div>
    </div>
</div>
@endsection
