@extends('layouts.admin')
@section('title', 'Riwayat Buku')

@section('content')
<a href="{{ route('admin.buku.index') }}" class="text-decoration-none small">&larr; Kembali ke Data Buku</a>

<div class="d-flex gap-3 align-items-start mt-2 mb-4">
    @if($buku->cover_url)
        <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" style="width:90px; height:120px; object-fit:cover;" class="rounded border">
    @else
        <div class="d-flex align-items-center justify-content-center bg-light border rounded text-muted" style="width:90px; height:120px; font-size:0.7rem;">Tanpa Cover</div>
    @endif
    <div>
        <h2 class="mb-1">{{ $buku->judul }}</h2>
        <div class="text-muted">{{ $buku->penulis }} &middot; {{ $buku->kategori }} &middot; {{ $buku->tahun_terbit }}</div>
        <div class="mt-2">
            <span class="badge rounded-pill bg-secondary">Total Stok: {{ $buku->stok }}</span>
            <span class="badge rounded-pill {{ $buku->isTersedia() ? 'bg-success' : 'bg-danger' }}">Tersedia: {{ $buku->stok_tersedia }}</span>
        </div>
    </div>
</div>

<h5>Riwayat Peminjaman</h5>
<div class="table-responsive"><table class="table table-hover bg-white">
    <thead>
        <tr><th>Mahasiswa</th><th>NIM</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Tgl Kembali</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse($riwayat as $r)
        <tr>
            <td>{{ $r->mahasiswa->nama }}</td>
            <td>{{ $r->mahasiswa->nim }}</td>
            <td>{{ $r->tanggal_pinjam->format('d-m-Y') }}</td>
            <td>{{ $r->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
            <td>{{ $r->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
            <td>
                @if($r->status == 'dipinjam')
                    <span class="badge rounded-pill bg-primary">Dipinjam</span>
                @elseif($r->status == 'terlambat')
                    <span class="badge rounded-pill bg-danger">Terlambat</span>
                @else
                    <span class="badge rounded-pill bg-success">Dikembalikan</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Buku ini belum pernah dipinjam.</td></tr>
        @endforelse
    </tbody>
</table></div>
{{ $riwayat->links() }}
@endsection
