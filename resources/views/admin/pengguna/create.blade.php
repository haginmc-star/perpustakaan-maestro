@extends('layouts.admin')
@section('title', 'Tambah Akun Admin')

@section('content')
<h2>Tambah Akun Admin/Staff</h2>
<form method="POST" action="{{ route('admin.pengguna.store') }}" class="card p-4">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required minlength="6">
    </div>
    <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
            <option value="staff">Staff (tidak bisa hapus data & kelola akun)</option>
            <option value="super_admin">Super Admin (akses penuh)</option>
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
