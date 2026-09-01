@extends('layouts.admin')
@section('title', 'Tambah Buku')

@section('content')
<h2>Tambah Buku</h2>
<form method="POST" action="{{ route('admin.buku.store') }}" class="card p-4" enctype="multipart/form-data">
    @csrf
    <div class="mb-3"><label class="form-label">Cover Buku (opsional)</label>
        <input type="file" name="cover" accept="image/*" class="form-control">
        <div class="form-text">Format gambar, maksimal 2MB.</div></div>
    <div class="mb-3"><label class="form-label">Judul</label>
        <input type="text" name="judul" value="{{ old('judul') }}" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Penulis</label>
        <input type="text" name="penulis" value="{{ old('penulis') }}" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Kategori</label>
        <input type="text" name="kategori" value="{{ old('kategori') }}" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Jumlah Stok (Eksemplar)</label>
        <input type="number" name="stok" min="1" value="{{ old('stok', 1) }}" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Sinopsis</label>
        <textarea name="sinopsis" class="form-control" rows="3">{{ old('sinopsis') }}</textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
