@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="section-eyebrow">Ringkasan</div>
<h2 class="mb-4">Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-ledger" style="--stat-color: var(--forest-700)">
            <div class="stat-num">{{ $totalBuku }}</div>
            <div class="stat-label">Total Judul Buku</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-ledger" style="--stat-color: var(--forest-500)">
            <div class="stat-num">{{ $bukuTersedia }}</div>
            <div class="stat-label">Judul Buku Tersedia</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-ledger" style="--stat-color: var(--brass-500)">
            <div class="stat-num">{{ $sedangDipinjam }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-ledger" style="--stat-color: var(--cardinal-600)">
            <div class="stat-num">{{ $mahasiswaDibekukan }}</div>
            <div class="stat-label">Mahasiswa Dibekukan</div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Rekap Bulanan</h5>
        <form method="GET" class="row g-2 mb-3">
            <div class="col-auto">
                <select name="bulan" class="form-select">
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" @selected($bulan == $b)>{{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <input type="number" name="tahun" value="{{ $tahun }}" class="form-control" style="width:100px">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary">Tampilkan</button>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.dashboard.export-pdf-bulanan', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-outline-secondary">📄 Export PDF Bulanan</a>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.dashboard.export-pdf-tahunan', ['tahun' => $tahun]) }}" class="btn btn-outline-secondary">📄 Export PDF Tahunan</a>
            </div>
        </form>

        <div class="row text-center">
            <div class="col-md-4">
                <div class="p-3 border rounded">
                    <div class="fs-3 fw-bold">{{ $jumlahPeminjamBulanIni }}</div>
                    <div class="text-muted">Orang Meminjam</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded">
                    <div class="fs-3 fw-bold">{{ $jumlahPengembalianBulanIni }}</div>
                    <div class="text-muted">Orang Mengembalikan</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded">
                    <div class="fs-3 fw-bold">{{ $jumlahTerlambatBulanIni }}</div>
                    <div class="text-muted">Orang Terlambat</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-body">
                <h5>Tren Peminjaman Tahun {{ $tahun }}</h5>
                <canvas id="chartTren" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5>Kategori Terpopuler</h5>
                @if($labelKategoriChart->isEmpty())
                    <p class="text-muted small mt-3">Belum ada data peminjaman untuk ditampilkan.</p>
                @else
                    <canvas id="chartKategori" height="180"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Peminjaman Aktif (Belum Dikembalikan)</h5>
        <table class="table table-sm table-hover align-middle">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Perpanjangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamanAktif as $p)
                    <tr>
                        <td>{{ $p->mahasiswa->nama }}</td>
                        <td>{{ $p->mahasiswa->nim }}</td>
                        <td>{{ $p->buku->judul }}</td>
                        <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                        <td>{{ $p->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                        <td>
                            @if($p->isTerlambat())
                                <span class="badge rounded-pill bg-danger">Terlambat</span>
                            @else
                                <span class="badge rounded-pill bg-primary">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge rounded-pill {{ $p->jumlah_perpanjangan > 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                {{ $p->jumlah_perpanjangan }}/{{ \App\Models\Peminjaman::MAX_PERPANJANGAN }}x
                            </span>
                        </td>
                        <td class="d-flex gap-1 flex-wrap">
                            <form action="{{ route('admin.peminjaman.kembalikan', $p) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-success" onclick="return confirm('Tandai buku ini sudah dikembalikan?')">Kembalikan</button>
                            </form>
                            @if($p->bisaDiperpanjang())
                                <form action="{{ route('admin.peminjaman.perpanjang', $p) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-warning" onclick="return confirm('Perpanjang peminjaman ini selama 7 hari?')">Perpanjang</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Tidak ada peminjaman aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const chartTrenCtx = document.getElementById('chartTren');
    if (chartTrenCtx) {
        new Chart(chartTrenCtx, {
            type: 'line',
            data: {
                labels: @json($labelBulanChart),
                datasets: [
                    {
                        label: 'Peminjam',
                        data: @json($dataPeminjamChart),
                        borderColor: '#24402F',
                        backgroundColor: 'rgba(36,64,47,0.1)',
                        tension: 0.3,
                    },
                    {
                        label: 'Pengembalian',
                        data: @json($dataPengembalianChart),
                        borderColor: '#3A5C46',
                        backgroundColor: 'rgba(58,92,70,0.1)',
                        tension: 0.3,
                    },
                    {
                        label: 'Terlambat',
                        data: @json($dataTerlambatChart),
                        borderColor: '#8B3A3A',
                        backgroundColor: 'rgba(139,58,58,0.1)',
                        tension: 0.3,
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }

    const chartKategoriCtx = document.getElementById('chartKategori');
    if (chartKategoriCtx) {
        new Chart(chartKategoriCtx, {
            type: 'bar',
            data: {
                labels: @json($labelKategoriChart),
                datasets: [{
                    label: 'Total Dipinjam',
                    data: @json($dataKategoriChart),
                    backgroundColor: '#B58A3D',
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }
</script>
@endpush
