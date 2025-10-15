@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">✏️ Edit Tagihan</h2>

    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Pelanggan</label>
                <select name="pelanggan_id" class="form-control">
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}" {{ $tagihan->pelanggan_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_pelanggan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Bulan</label>
                <select name="bulan_id" class="form-control">
                    @foreach($bulans as $b)
                        <option value="{{ $b->id }}" {{ $tagihan->bulan_id == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_bulan_tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Tarif</label>
                <select name="tarif_id" class="form-control">
                    @foreach($tarifs as $t)
                        <option value="{{ $t->id }}" {{ $tagihan->tarif_id == $t->id ? 'selected' : '' }}>
                            Rp {{ number_format($t->harga_per_m3,0,',','.') }}/m³
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Pemakaian (m³)</label>
                <input type="number" name="pemakaian" class="form-control" value="{{ $tagihan->pemakaian }}">
            </div>
            <div class="col-md-4">
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" name="tanggal_jatuh_tempo" class="form-control" value="{{ $tagihan->tanggal_jatuh_tempo }}">
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
