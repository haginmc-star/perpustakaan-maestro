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
        .stat-row td { text-align: center; font-size: 16px; font-weight: bold; padding: 12px; }
        .stat-label { font-size: 10px; color: #666; text-transform: uppercase; }
        .footer { margin-top: 30px; font-size: 9px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Rekap Bulanan Perpustakaan Universitas</h1>
    <div class="sub">Periode: {{ $namaBulan }} {{ $tahun }} &middot; Dicetak: {{ now()->format('d-m-Y H:i') }}</div>

    <table>
        <tr class="stat-row">
            <td style="width:33%">
                {{ $jumlahPeminjamBulanIni }}
                <div class="stat-label">Orang Meminjam</div>
            </td>
            <td style="width:33%">
                {{ $jumlahPengembalianBulanIni }}
                <div class="stat-label">Orang Mengembalikan</div>
            </td>
            <td style="width:34%">
                {{ $jumlahTerlambatBulanIni }}
                <div class="stat-label">Orang Terlambat</div>
            </td>
        </tr>
    </table>

    <h3>Detail Transaksi Peminjaman Bulan Ini</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mahasiswa</th>
                <th>NIM</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detail as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $d->mahasiswa->nama }}</td>
                <td>{{ $d->mahasiswa->nim }}</td>
                <td>{{ $d->buku->judul }}</td>
                <td>{{ $d->tanggal_pinjam->format('d-m-Y') }}</td>
                <td>{{ $d->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                <td>{{ $d->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ ucfirst($d->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;">Tidak ada transaksi bulan ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dokumen dibuat otomatis oleh Sistem Perpustakaan Universitas.</div>
</body>
</html>
