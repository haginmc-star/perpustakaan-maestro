@extends('layouts.admin')
@section('title', 'Data Buku')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Data Buku</h2>
    <a href="{{ route('admin.buku.create') }}" class="btn btn-primary">+ Tambah Buku</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari judul / penulis...">
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Cari</button></div>
</form>

<table class="table table-hover bg-white">
    <thead>
        <tr><th>Cover</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok Total</th><th>Tersedia</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @forelse($bukus as $b)
        <tr>
            <td>
                @if($b->cover_url)
                    <img src="{{ $b->cover_url }}" alt="cover" style="width:36px; height:48px; object-fit:cover;" class="rounded border">
                @else
                    <div class="bg-light border rounded text-muted d-flex align-items-center justify-content-center" style="width:36px; height:48px; font-size:0.55rem;">-</div>
                @endif
            </td>
            <td>{{ $b->judul }}</td>
            <td>{{ $b->penulis }}</td>
            <td>{{ $b->kategori }}</td>
            <td>{{ $b->stok }}</td>
            <td>
                @if($b->isTersedia())
                    <span class="badge bg-success">{{ $b->stok_tersedia }}</span>
                @else
                    <span class="badge bg-secondary">0 (habis)</span>
                @endif
            </td>
            <td class="d-flex gap-1">
                <a href="{{ route('admin.buku.riwayat', $b) }}" class="btn btn-sm btn-outline-secondary">Riwayat</a>
                <a href="{{ route('admin.buku.edit', $b) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                @if(auth()->user()->role === 'super_admin')
                <form action="{{ route('admin.buku.destroy', $b) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-dark">Hapus</button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $bukus->links() }}
@endsection
