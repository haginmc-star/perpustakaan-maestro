@extends('layouts.guest')
@section('title', 'Cek Peminjaman')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="section-eyebrow">Layanan Mandiri</div>
        <h2 class="mb-3">Cek Status Peminjaman</h2>
        <p class="text-muted">Masukkan NIM kamu untuk melihat buku apa saja yang sedang dipinjam dan kapan harus dikembalikan.</p>

        <form method="GET" class="d-flex gap-2 mb-4">
            <input type="text" name="nim" value="{{ request('nim') }}" class="form-control" placeholder="Masukkan NIM..." required>
            <button class="btn btn-primary">Cek</button>
        </form>

        @if(request()->filled('nim'))
            @if(!$mahasiswa)
                <div class="alert alert-warning">NIM tidak ditemukan. Pastikan NIM yang dimasukkan sudah benar.</div>
            @else
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-1">{{ $mahasiswa->nama }}</h5>
                        <div class="text-muted mb-2">{{ $mahasiswa->nim }} &middot; {{ $mahasiswa->jurusan }}</div>
                        @if($mahasiswa->status == 'aktif')
                            <span class="badge rounded-pill bg-success">Status Akun: Aktif</span>
                        @else
                            <span class="badge rounded-pill bg-danger">
                                Akun Dibekukan sampai {{ $mahasiswa->tanggal_selesai_bekuan?->format('d-m-Y') ?? '(belum ditentukan)' }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="table-responsive"><table class="table table-hover bg-white">
                    <thead>
                        <tr><th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                        <tr>
                            <td>{{ $p->buku->judul }}</td>
                            <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                            <td>{{ $p->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                            <td>
                                @if($p->status == 'dipinjam')
                                    <span class="badge rounded-pill bg-primary">Dipinjam</span>
                                @elseif($p->status == 'terlambat')
                                    <span class="badge rounded-pill bg-danger">Terlambat</span>
                                @else
                                    <span class="badge rounded-pill bg-success">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat peminjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table></div>
            @endif
        @endif
    </div>
</div>
@endsection
