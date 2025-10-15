@extends('layout')

@section('content')
<div class="container">
    <h2>Hasil Pembayaran</h2>
    <div class="card p-3">
        <p><strong>Pelanggan:</strong> {{ $tagihan->pelanggan->nama_pelanggan }}</p>
        <p><strong>Total Bayar:</strong> Rp {{ number_format($total, 0, ',', '.') }}</p>
        <p><strong>Uang Kembalian:</strong> Rp {{ number_format($kembalian, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($tagihan->status) }}</p>
        <a href="{{ route('tagihan.index') }}" class="btn btn-primary">Kembali ke Daftar Tagihan</a>
    </div>
</div>
@endsection
