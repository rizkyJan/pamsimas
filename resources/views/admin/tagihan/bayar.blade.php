@extends('layout')

@section('content')
<div class="container">
    <h2>Bayar Tagihan - {{ $tagihan->pelanggan->nama_pelanggan }}</h2>

    <div class="card p-3">
        <p><strong>Bulan:</strong> {{ $tagihan->bulan->nama_bulan_tahun }}</p>
        <p><strong>Total Tagihan:</strong> Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
        <p><strong>Jatuh Tempo:</strong> {{ $tagihan->tanggal_jatuh_tempo }}</p>

        <form action="{{ route('tagihan.prosesBayar', $tagihan->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Jumlah Bayar</label>
                <input type="number" step="100" name="jumlah_bayar" class="form-control" required>
            </div>
            <button class="btn btn-success">Proses Pembayaran</button>
        </form>
    </div>

    <div class="mt-3">
        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali ke Tagihan</a>
    </div>
</div>
@endsection