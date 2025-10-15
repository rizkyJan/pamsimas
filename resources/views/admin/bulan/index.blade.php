@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-3">Data Bulan</h2>
    <a href="{{ route('bulan.create') }}" class="btn btn-primary mb-3">➕ Tambah Bulan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Bulan-Tahun</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bulans as $index => $b)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $b->nama_bulan_tahun }}</td>
                <td>{{ $b->bulan }}</td>
                <td>{{ $b->tahun }}</td>
                <td>
                    <a href="{{ route('bulan.edit', $b->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('bulan.destroy', $b->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Hapus bulan ini?')" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Belum ada data bulan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
