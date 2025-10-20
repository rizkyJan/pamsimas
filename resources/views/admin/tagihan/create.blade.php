@extends('layout')

@section('content')
<div class="container">
    <h3>Tambah Tagihan Baru</h3>

    {{-- Pesan Error Global dari Controller --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Pesan Validasi --}}
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
                    {{-- Opsi bulan akan diisi oleh JavaScript --}}
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
                        {{-- PERBAIKAN FINAL: Menggunakan 'harga_per_m3' sesuai nama kolom database --}}
                        <option 
                            value="{{ $t->id }}" 
                            data-harga="{{ $t->harga_per_m3 }}" 
                            data-denda="{{ $t->biaya_denda }}" 
                            data-beban="{{ $t->biaya_beban }}">
                            Rp {{ number_format($t->harga_per_m3, 0, ',', '.') }} / m³ 
                            (Beban: Rp {{ number_format($t->biaya_beban, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="form-control" required>
            </div>
        </div>

        {{-- Input hidden tidak diperlukan lagi karena controller sudah menghitung semuanya --}}

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
                <th>Biaya Beban</th>
                <th>Subtotal</th>
                <th>Denda</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="tMeterAwal">-</td>
                <td id="tMeterAkhir">-</td>
                <td id="tPemakaian">-</td>
                <td id="tTarif">-</td>
                <td id="tBeban">-</td>
                <td id="tSubtotal">-</td>
                <td id="tDenda">-</td>
                <td id="tTotalBayar">-</td>
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

    function initEventListeners() {
        pelangganSelect.addEventListener('change', fetchPelangganData);
        meterAkhirInput.addEventListener('input', hitungPreview);
        tarifSelect.addEventListener('change', hitungPreview);
        jatuhTempoInput.addEventListener('change', hitungPreview);
    }

    function fetchPelangganData() {
        const pelangganId = this.value;
        if (!pelangganId) {
            resetForm();
            return;
        }

        fetch(`/admin/get-data-pelanggan/${pelangganId}`)
            .then(res => res.json())
            .then(data => {
                meterAwalInput.value = data.meter_awal ?? 0;
                
                let bulanOptions = '<option value="">-- Pilih Bulan --</option>';
                data.bulanTersedia.forEach(b => {
                    bulanOptions += `<option value="${b.id}">${b.nama_bulan_tahun}</option>`;
                });
                bulanSelect.innerHTML = bulanOptions;
                
                hitungPreview();
            });
    }

    function formatRupiah(angka) {
        return 'Rp ' + (angka ? angka.toLocaleString('id-ID') : '0');
    }
    
    function resetForm() {
        meterAwalInput.value = '';
        meterAkhirInput.value = '';
        pemakaianInput.value = '';
        bulanSelect.innerHTML = '<option value="">-- Pilih Bulan --</option>';
        hitungPreview();
    }

    function hitungPreview() {
        const awal = parseFloat(meterAwalInput.value) || 0;
        const akhir = parseFloat(meterAkhirInput.value) || 0;
        
        const selectedTarif = tarifSelect.selectedOptions[0];
        if (!selectedTarif) return;
        
        const tarif = parseFloat(selectedTarif.dataset.harga) || 0;
        const dendaTarif = parseFloat(selectedTarif.dataset.denda) || 0;
        const beban = parseFloat(selectedTarif.dataset.beban) || 0;
        
        const jatuhTempoValue = jatuhTempoInput.value;
        const jatuhTempo = jatuhTempoValue ? new Date(jatuhTempoValue) : null;
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        let pemakaian = 0;
        if (akhir >= awal) {
            pemakaian = akhir - awal;
            errorMeter.style.display = 'none';
            meterAkhirInput.classList.remove('is-invalid');
            btnSimpan.disabled = false;
        } else {
            pemakaian = 0; // Pastikan pemakaian 0 jika tidak valid
            errorMeter.style.display = 'block';
            meterAkhirInput.classList.add('is-invalid');
            btnSimpan.disabled = true;
        }
        pemakaianInput.value = pemakaian;

        const subtotal = (pemakaian * tarif) + beban;

        let totalDenda = 0;
        if (jatuhTempo && today > jatuhTempo) {
            totalDenda = dendaTarif;
        }

        const totalBayar = subtotal + totalDenda;

        document.getElementById('tMeterAwal').innerText = awal;
        document.getElementById('tMeterAkhir').innerText = akhir;
        document.getElementById('tPemakaian').innerText = pemakaian;
        document.getElementById('tTarif').innerText = formatRupiah(tarif);
        document.getElementById('tBeban').innerText = formatRupiah(beban);
        document.getElementById('tSubtotal').innerText = formatRupiah(subtotal);
        document.getElementById('tDenda').innerText = formatRupiah(totalDenda);
        document.getElementById('tTotalBayar').innerText = formatRupiah(totalBayar);
    }

    initEventListeners();
    hitungPreview();
});
</script>
@endsection

