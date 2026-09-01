@extends('layouts.admin')
@section('title', 'Edit Buku')

@section('content')
<h2>Edit Buku</h2>
<form method="POST" action="{{ route('admin.buku.update', $buku) }}" class="card p-4" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Cover Buku</label>
        @if($buku->cover_url)
            <div class="mb-2"><img src="{{ $buku->cover_url }}" style="width:80px; height:110px; object-fit:cover;" class="rounded border"></div>
        @endif
        <input type="file" name="cover" accept="image/*" class="form-control">
        <div class="form-text">Kosongkan kalau tidak ingin mengubah cover.</div>
    </div>
    <div class="mb-3"><label class="form-label">Judul</label>
        <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Penulis</label>
        <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Kategori</label>
        <input type="text" name="kategori" value="{{ old('kategori', $buku->kategori) }}" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Jumlah Stok (Eksemplar)</label>
        <input type="number" name="stok" min="0" value="{{ old('stok', $buku->stok) }}" class="form-control" required>
        <div class="form-text">Sedang dipinjam: {{ $buku->stok - $buku->stok_tersedia }} eksemplar.</div>
    </div>
    <div class="mb-3"><label class="form-label">Sinopsis</label>
        <textarea name="sinopsis" class="form-control" rows="3">{{ old('sinopsis', $buku->sinopsis) }}</textarea></div>
    <button class="btn btn-primary">Perbarui</button>
    <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
