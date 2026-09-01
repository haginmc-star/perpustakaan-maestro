<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1C1B17; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 0; color: #16281F; }
        .sub { color: #666; margin-top: 2px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background: #16281F; color: #fff; }
        td.num { text-align: center; }
        tr.total td { font-weight: bold; background: #F2F0E4; }
        .stat-row td { text-align: center; font-size: 16px; font-weight: bold; padding: 12px; }
        .stat-label { font-size: 10px; color: #666; text-transform: uppercase; }
        .footer { margin-top: 30px; font-size: 9px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Rekap Tahunan Perpustakaan Universitas</h1>
    <div class="sub">Tahun: {{ $tahun }} &middot; Dicetak: {{ now()->format('d-m-Y H:i') }}</div>

    <table>
        <tr class="stat-row">
            <td style="width:33%">
                {{ $totalPeminjam }}
                <div class="stat-label">Total Peminjam Setahun</div>
            </td>
            <td style="width:33%">
                {{ $totalPengembalian }}
                <div class="stat-label">Total Pengembalian</div>
            </td>
            <td style="width:34%">
                {{ $totalTerlambat }}
                <div class="stat-label">Total Terlambat</div>
            </td>
        </tr>
    </table>

    <h3>Rincian per Bulan</h3>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Peminjam</th>
                <th>Jumlah Pengembalian</th>
                <th>Jumlah Terlambat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($breakdown as $row)
            <tr>
                <td>{{ $row['namaBulan'] }}</td>
                <td class="num">{{ $row['jumlahPeminjamBulanIni'] }}</td>
                <td class="num">{{ $row['jumlahPengembalianBulanIni'] }}</td>
                <td class="num">{{ $row['jumlahTerlambatBulanIni'] }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td>TOTAL</td>
                <td class="num">{{ $totalPeminjam }}</td>
                <td class="num">{{ $totalPengembalian }}</td>
                <td class="num">{{ $totalTerlambat }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">Dokumen dibuat otomatis oleh Sistem Perpustakaan Universitas.</div>
</body>
</html>
