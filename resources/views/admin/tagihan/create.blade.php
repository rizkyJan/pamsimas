@extends('layout')

@section('content')
<div class="container">
    <h3>Tambah Tagihan Baru</h3>

    {{-- ✅ Pesan Error Global dari Controller --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ✅ Pesan Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tagihan.store') }}" method="POST" id="formTagihan">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Pelanggan</label>
                <select name="pelanggan_id" id="pelanggan_id" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($pelanggans as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label>Bulan</label>
                <select name="bulan_id" id="bulan_id" class="form-control" required>
                    <option value="">-- Pilih Bulan --</option>
                    @foreach ($bulans as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bulan_tahun }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Meter Awal</label>
                <input type="number" name="meter_awal" id="meter_awal" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label>Meter Akhir</label>
                <input type="number" name="meter_akhir" id="meter_akhir" class="form-control" required>
                {{-- ⚠️ Pesan error lokal --}}
                <small id="errorMeter" class="text-danger" style="display:none;">
                    ⚠️ Meter akhir tidak boleh lebih kecil dari meter awal.
                </small>
            </div>
            <div class="col-md-4">
                <label>Pemakaian (m³)</label>
                <input type="number" name="pemakaian" id="pemakaian" class="form-control" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Tarif</label>
                <select name="tarif_id" id="tarif_id" class="form-control" required>
                    @foreach ($tarifs as $t)
                        <option value="{{ $t->id }}" data-harga="{{ $t->harga_per_m3 }}" data-denda="{{ $t->biaya_denda }}">
                            Rp {{ number_format($t->harga_per_m3, 0, ',', '.') }} / m³
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="form-control" required>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success" id="btnSimpan">Simpan</button>
            <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>

    <hr>
    <h5>Preview Tagihan</h5>
    <table class="table table-bordered" id="previewTable">
        <thead>
            <tr>
                <th>Meter Awal</th>
                <th>Meter Akhir</th>
                <th>Pemakaian (m³)</th>
                <th>Tarif per m³</th>
                <th>Denda</th>
                <th>Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="tMeterAwal">-</td>
                <td id="tMeterAkhir">-</td>
                <td id="tPemakaian">-</td>
                <td id="tTarif">-</td>
                <td id="tDenda">-</td>
                <td id="tTotal">-</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pelangganSelect = document.getElementById('pelanggan_id');
    const bulanSelect = document.getElementById('bulan_id');
    const meterAwalInput = document.getElementById('meter_awal');
    const meterAkhirInput = document.getElementById('meter_akhir');
    const pemakaianInput = document.getElementById('pemakaian');
    const tarifSelect = document.getElementById('tarif_id');
    const jatuhTempoInput = document.getElementById('tanggal_jatuh_tempo');
    const errorMeter = document.getElementById('errorMeter');
    const btnSimpan = document.getElementById('btnSimpan');

    pelangganSelect.addEventListener('change', function () {
        const pelangganId = this.value;
        if (!pelangganId) return;

        fetch(`/admin/get-data-pelanggan/${pelangganId}`)
            .then(res => res.json())
            .then(data => {
                meterAwalInput.value = data.meter_awal ?? 0;
                bulanSelect.innerHTML = '<option value="">-- Pilih Bulan --</option>';
                data.bulanTersedia.forEach(b => {
                    bulanSelect.innerHTML += `<option value="${b.id}">${b.nama_bulan_tahun}</option>`;
                });
                hitungPreview();
            });
    });

    [meterAkhirInput, tarifSelect, jatuhTempoInput].forEach(el => {
        el.addEventListener('input', hitungPreview);
    });

    function hitungPreview() {
        const awal = parseFloat(meterAwalInput.value) || 0;
        const akhir = parseFloat(meterAkhirInput.value) || 0;
        const tarif = parseFloat(tarifSelect.selectedOptions[0].dataset.harga) || 0;
        const denda = parseFloat(tarifSelect.selectedOptions[0].dataset.denda) || 0;
        const jatuhTempo = new Date(jatuhTempoInput.value);
        const today = new Date();

        // 🔹 Validasi meter akhir
        if (akhir < awal) {
            errorMeter.style.display = 'block';
            meterAkhirInput.classList.add('is-invalid');
            pemakaianInput.value = 0;
            btnSimpan.disabled = true;
        } else {
            errorMeter.style.display = 'none';
            meterAkhirInput.classList.remove('is-invalid');
            pemakaianInput.value = akhir - awal;
            btnSimpan.disabled = false;
        }

        const pemakaian = parseFloat(pemakaianInput.value) || 0;
        let totalDenda = 0;
        if (jatuhTempo && today > jatuhTempo) totalDenda = denda;

        const total = (pemakaian * tarif) + totalDenda;

        // 🔹 Update preview
        document.getElementById('tMeterAwal').innerText = awal;
        document.getElementById('tMeterAkhir').innerText = akhir;
        document.getElementById('tPemakaian').innerText = pemakaian;
        document.getElementById('tTarif').innerText = 'Rp ' + tarif.toLocaleString('id-ID');
        document.getElementById('tDenda').innerText = 'Rp ' + totalDenda.toLocaleString('id-ID');
        document.getElementById('tTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
});
</script>
@endsection
