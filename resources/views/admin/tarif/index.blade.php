@extends('layout')

@section('content')
<div class="container">
    <h2>Data Tarif</h2>
    <a href="{{ route('admin.tarif.create') }}" class="btn btn-primary mb-3">Tambah Tarif</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Tarif</th>
                <th>Biaya Beban</th>
                <th>Biaya Denda</th>
                <th>Harga/m³</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tarifs as $t)
            <tr>
                <td>{{ $t->nama_tarif }}</td>
                <td>Rp {{ number_format($t->biaya_beban, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($t->biaya_denda, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($t->harga_per_m3, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('admin.tarif.edit', $t->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.tarif.destroy', $t->id) }}" method="POST" style="display:inline-block;">
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
