@extends('layout')

@section('content')
<div class="container">
    <h2>Detail Tagihan</h2>
    <div class="card p-3">
        <p><strong>Nama Pelanggan:</strong> {{ $tagihan->pelanggan->nama_pelanggan }}</p>
        <p><strong>Bulan:</strong> {{ $tagihan->bulan->nama_bulan_tahun }}</p>
        <p><strong>Pemakaian:</strong> {{ $tagihan->pemakaian }} m³</p>
        <p><strong>Total Tagihan:</strong> Rp {{ number_format($tagihan->total_tagihan,0,',','.') }}</p>
        <p><strong>Tanggal Jatuh Tempo:</strong> {{ $tagihan->tanggal_jatuh_tempo }}</p>
        <p><strong>Status:</strong> {{ strtoupper($tagihan->status) }}</p>

        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
