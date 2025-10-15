@extends('layout')

@section('content')
<div class="container">
    <h2>Data Tagihan</h2>
    <a href="{{ route('tagihan.create') }}" class="btn btn-primary mb-3">Tambah Tagihan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Bulan</th>
                <th>Pemakaian (m³)</th>
                <th>Total Tagihan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tagihans as $t)
            <tr>
                <td>{{ $t->pelanggan->nama_pelanggan }}</td>
                <td>{{ $t->bulan->nama_bulan_tahun }}</td>
                <td>{{ $t->pemakaian }}</td>
                <td>Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                <td>{{ ucfirst($t->status) }}</td>
                <td>
                    <a href="{{ route('tagihan.show', $t->id) }}" class="btn btn-info btn-sm">Detail</a>
                    @if($t->status == 'belum bayar')
                        <a href="{{ route('tagihan.bayar', $t->id) }}" class="btn btn-success btn-sm">Bayar</a>
                    @endif
                    <a href="{{ route('tagihan.edit', $t->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('tagihan.destroy', $t->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
