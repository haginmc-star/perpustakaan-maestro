@extends('layouts.admin')
@section('title', 'Riwayat Mahasiswa')

@section('content')
<a href="{{ route('admin.mahasiswa.index') }}" class="text-decoration-none small">&larr; Kembali ke Data Mahasiswa</a>

<div class="mt-2 mb-4">
    <h2 class="mb-1">{{ $mahasiswa->nama }}</h2>
    <div class="text-muted">{{ $mahasiswa->nim }} &middot; {{ $mahasiswa->jurusan }}</div>
    <div class="mt-2 d-flex gap-2">
        @if($mahasiswa->status == 'aktif')
            <span class="badge rounded-pill bg-success">Status: Aktif</span>
        @else
            <span class="badge rounded-pill bg-danger">Status: Dibekukan</span>
        @endif
        <span class="badge rounded-pill bg-secondary">Total Pernah Terlambat: {{ $totalTerlambat }}x</span>
    </div>
</div>

<h5>Riwayat Peminjaman</h5>
<table class="table table-hover bg-white">
    <thead>
        <tr><th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Tgl Kembali</th><th>Status</th><th>Perpanjangan</th></tr>
    </thead>
    <tbody>
        @forelse($riwayat as $r)
        <tr>
            <td>{{ $r->buku->judul }}</td>
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
            <td><span class="badge rounded-pill bg-secondary">{{ $r->jumlah_perpanjangan }}x</span></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Mahasiswa ini belum pernah meminjam buku.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $riwayat->links() }}
@endsection
