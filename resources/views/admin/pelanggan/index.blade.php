@extends('layout')
@section('content')
    <h3>Pelanggan</h3>
    <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary mb-2">Tambah</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggans as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->nama_pelanggan }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>
                        <a href="{{ route('admin.pelanggan.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.pelanggan.destroy', $p->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $pelanggans->links() }}
@endsection
