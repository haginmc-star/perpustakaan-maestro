@extends('layouts.admin')
@section('title', 'Data Peminjaman')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Data Peminjaman</h2>
    <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary">+ Pinjam Buku Baru</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama / NIM / judul buku...">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">-- Semua Status --</option>
            <option value="dipinjam" @selected(request('status')=='dipinjam')>Dipinjam</option>
            <option value="dikembalikan" @selected(request('status')=='dikembalikan')>Dikembalikan</option>
            <option value="terlambat" @selected(request('status')=='terlambat')>Terlambat</option>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Cari</button></div>
</form>

<table class="table table-hover bg-white align-middle">
    <thead>
        <tr>
            <th>Mahasiswa</th><th>NIM</th><th>Buku</th><th>Tgl Pinjam</th>
            <th>Jatuh Tempo</th><th>Tgl Kembali</th><th>Status</th><th>Perpanjangan</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($peminjamans as $p)
        <tr>
            <td>{{ $p->mahasiswa->nama }}</td>
            <td>{{ $p->mahasiswa->nim }}</td>
            <td>{{ $p->buku->judul }}</td>
            <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
            <td>{{ $p->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
            <td>{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
            <td>
                @if($p->status == 'dipinjam')
                    <span class="badge rounded-pill bg-primary">Dipinjam</span>
                @elseif($p->status == 'terlambat')
                    <span class="badge rounded-pill bg-danger">Terlambat</span>
                @else
                    <span class="badge rounded-pill bg-success">Dikembalikan</span>
                @endif
            </td>
            <td>
                <span class="badge rounded-pill {{ $p->jumlah_perpanjangan > 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">
                    {{ $p->jumlah_perpanjangan }}/{{ \App\Models\Peminjaman::MAX_PERPANJANGAN }}x
                </span>
            </td>
            <td class="d-flex gap-1 flex-wrap">
                @if(!$p->tanggal_kembali)
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
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center text-muted">Belum ada data peminjaman.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $peminjamans->links() }}
@endsection
