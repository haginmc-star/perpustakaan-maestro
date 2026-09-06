@extends('layouts.admin')
@section('title', 'Pengaturan Situs')

@section('content')
<h2 class="mb-3">Pengaturan Situs</h2>

<div class="card p-4" style="max-width: 600px;">
    <h5>Mode Pemeliharaan (Maintenance Mode)</h5>
    <p class="text-muted small">
        Kalau diaktifkan, semua pengunjung (guest) akan melihat halaman "Sedang Pemeliharaan"
        dan tidak bisa mengakses Tentang, Katalog, atau Cek Peminjaman. Admin/staff yang sudah
        login tetap bisa mengakses semua fitur seperti biasa.
    </p>

    <div class="mb-3">
        Status saat ini:
        @if($setting->is_maintenance)
            <span class="badge rounded-pill bg-danger">TERTUTUP untuk pengunjung</span>
        @else
            <span class="badge rounded-pill bg-success">TERBUKA untuk pengunjung</span>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.pengaturan.toggle') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Pesan yang ditampilkan ke pengunjung (opsional)</label>
            <textarea name="pesan_maintenance" class="form-control" rows="2">{{ $setting->pesan_maintenance }}</textarea>
        </div>

        @if($setting->is_maintenance)
            <button class="btn btn-success" onclick="return confirm('Buka kembali web untuk pengunjung?')">
                Buka Kembali untuk Pengunjung
            </button>
        @else
            <button class="btn btn-danger" onclick="return confirm('Tutup web untuk pengunjung? Hanya admin/staff yang bisa akses setelah ini.')">
                Tutup Web untuk Pengunjung
            </button>
        @endif
    </form>
</div>
@endsection
