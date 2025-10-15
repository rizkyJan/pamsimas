@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">📊 Dashboard PAMSIMAS Tirta Gianti</h2>

    {{-- CARD STATISTIK --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <h5>Total Pelanggan</h5>
                    <h3>{{ $totalPelanggan }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <h5>Total Tagihan</h5>
                    <h3>{{ $totalTagihan }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <h5>Sudah Bayar</h5>
                    <h3>{{ $sudahBayar }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <h5>Belum Bayar</h5>
                    <h3>{{ $belumBayar }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD PENDAPATAN --}}
    <div class="mb-5">
        <div class="card bg-dark text-white shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5>Total Pendapatan</h5>
                    <h2>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                </div>
                <i class="bi bi-cash-stack" style="font-size: 3rem;"></i>
            </div>
        </div>
    </div>

    {{-- GRAFIK TAGIHAN --}}
    <div class="card mb-5">
        <div class="card-header bg-light">
            <strong>Grafik Total Tagihan per Bulan</strong>
        </div>
        <div class="card-body">
            <canvas id="tagihanChart" height="100"></canvas>
        </div>
    </div>

    {{-- TABEL 5 TAGIHAN TERAKHIR --}}
    <div class="card">
        <div class="card-header bg-light">
            <strong>5 Tagihan Terbaru</strong>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Pelanggan</th>
                        <th>Bulan</th>
                        <th>Pemakaian (m³)</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tagihanTerbaru as $t)
                    <tr>
                        <td>{{ $t->pelanggan->nama_pelanggan }}</td>
                        <td>{{ $t->bulan->nama_bulan_tahun }}</td>
                        <td>{{ $t->pemakaian }}</td>
                        <td>Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $t->status == 'sudah bayar' ? 'success' : 'warning' }}">
                                {{ strtoupper($t->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    @if($tagihanTerbaru->isEmpty())
                    <tr><td colspan="5" class="text-center">Belum ada data tagihan</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('tagihanChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bulan) !!},
            datasets: [{
                label: 'Total Tagihan (Rp)',
                data: {!! json_encode($total) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
