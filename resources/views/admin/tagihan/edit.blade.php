@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">✏️ Edit Tagihan</h2>

    {{-- PENTING: Tampilkan pesan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Pelanggan</label>
                <select name="pelanggan_id" class="form-control">
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}" {{ $tagihan->pelanggan_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_pelanggan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label>Bulan</label>
                <select name="bulan_id" class="form-control">
                    @foreach($bulans as $b)
                        <option value="{{ $b->id }}" {{ $tagihan->bulan_id == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_bulan_tahun }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            {{-- TAMBAHKAN KEMBALI INPUT METER --}}
            <div class="col-md-3">
                <label>Meter Awal</label>
                <input type="number" name="meter_awal" class="form-control" value="{{ $tagihan->meter_awal }}">
            </div>
            <div class="col-md-3">
                <label>Meter Akhir</label>
                <input type="number" name="meter_akhir" class="form-control" value="{{ $tagihan->meter_akhir }}">
            </div>

            <div class="col-md-3">
                <label>Tarif</label>
                <select name="tarif_id" class="form-control">
                    @foreach($tarifs as $t)
                        <option value="{{ $t->id }}" {{ $tagihan->tarif_id == $t->id ? 'selected' : '' }}>
                            Rp {{ number_format($t->harga_per_m3,0,',','.') }}/m³
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" name="tanggal_jatuh_tempo" class="form-control" value="{{ $tagihan->tanggal_jatuh_tempo }}">
            </div>
        </div>
        
        {{-- HAPUS INPUT PEMAKAIAN KARENA SUDAH DIHITUNG OTOMATIS OLEH CONTROLLER --}}

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
