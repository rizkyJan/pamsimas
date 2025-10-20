<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 14px;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .details-table th {
            width: 30%;
            background-color: #f2f2f2;
        }

        .details-table,
        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
        }

        .total {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Bukti Pembayaran Tagihan Air</h1>
            <p>PAMSIMAS TIRTA GIANTI Desa Janti</p>
        </div>

        <table class="details-table">
            <tr>
                <th>No. Tagihan</th>
                <td>#{{ str_pad($tagihan->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <th>Tanggal Cetak</th>
                <td>{{ $tanggal_cetak }}</td>
            </tr>
            <tr>
                <th>Nama Pelanggan</th>
                <td>{{ $tagihan->pelanggan->nama_pelanggan }}</td>
            </tr>
            <tr>
                <th>Bulan Tagihan</th>
                <td>{{ $tagihan->bulan->nama_bulan_tahun }}</td>
            </tr>
            <tr>
                <th>Meter Awal</th>
                <td>{{ $tagihan->meter_awal }} m³</td>
            </tr>
            <tr>
                <th>Meter Akhir</th>
                <td>{{ $tagihan->meter_akhir }} m³</td>
            </tr>
            <tr>
                <th>Total Pemakaian</th>
                <td>{{ $tagihan->pemakaian }} m³</td>
            </tr>
            <tr>
                <th>Total Tagihan</th>
                <td>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Denda</th>
                <td>Rp {{ number_format($tagihan->denda, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th class="total">Total Bayar</th>
                <td class="total">Rp {{ number_format($tagihan->total_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>LUNAS</td>
            </tr>
        </table>

        <div class="footer">
            <p>Terima kasih telah melakukan pembayaran tepat waktu.</p>
            <p>Simpan bukti ini sebagai tanda pembayaran yang sah.</p>
        </div>
    </div>
</body>

</html>
