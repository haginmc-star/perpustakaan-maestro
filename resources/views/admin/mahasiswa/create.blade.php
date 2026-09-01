@extends('layouts.admin')
@section('title', 'Tambah Mahasiswa')

@section('content')
<h2>Tambah Mahasiswa</h2>
<form method="POST" action="{{ route('admin.mahasiswa.store') }}" class="card p-4">
    @csrf
    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input type="text" name="nim" value="{{ old('nim') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Jurusan</label>
        <input type="text" name="jurusan" value="{{ old('jurusan') }}" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">No. HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control">
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
