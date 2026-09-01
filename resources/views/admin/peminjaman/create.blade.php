@extends('layouts.admin')
@section('title', 'Pinjam Buku Baru')

@section('content')
<h2>Catat Peminjaman Buku Baru</h2>

<form method="POST" action="{{ route('admin.peminjaman.store') }}" class="card p-4">
    @csrf
    <div class="mb-3">
        <label class="form-label">Mahasiswa</label>
        <select name="mahasiswa_id" class="form-select" required>
            <option value="">-- Pilih Mahasiswa --</option>
            @foreach($mahasiswas as $m)
                <option value="{{ $m->id }}" {{ $m->status == 'dibekukan' ? 'disabled' : '' }}>
                    {{ $m->nim }} - {{ $m->nama }} {{ $m->status == 'dibekukan' ? '(DIBEKUKAN)' : '' }}
                </option>
            @endforeach
        </select>
        <div class="form-text">Mahasiswa berstatus "dibekukan" tidak bisa dipilih.</div>
    </div>

    <div class="mb-3">
        <label class="form-label">Buku</label>
        <select name="buku_id" class="form-select" required>
            <option value="">-- Pilih Buku --</option>
            @foreach($bukus as $b)
                <option value="{{ $b->id }}">{{ $b->judul }} (tersedia: {{ $b->stok_tersedia }})</option>
            @endforeach
        </select>
        <div class="form-text">Hanya buku dengan stok tersedia yang muncul di sini.</div>
    </div>

    <p class="text-muted">Durasi peminjaman otomatis 7 hari dari hari ini.</p>

    <button class="btn btn-primary">Simpan Peminjaman</button>
    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
