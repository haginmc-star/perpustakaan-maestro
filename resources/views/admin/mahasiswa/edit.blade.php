@extends('layouts.admin')
@section('title', 'Edit Mahasiswa')

@section('content')
<h2>Edit Mahasiswa</h2>
<form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" class="card p-4">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Jurusan</label>
        <input type="text" name="jurusan" value="{{ old('jurusan', $mahasiswa->jurusan) }}" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">No. HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp) }}" class="form-control">
    </div>
    <button class="btn btn-primary">Perbarui</button>
    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
