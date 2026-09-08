<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 - Akses Ditolak</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="hero-library d-flex align-items-center" style="min-height: 100vh;">
    <div class="container text-center position-relative">
        <div class="hero-eyebrow">Perpustakaan Universitas &middot; Error 403</div>
        <h1 class="hero-title">Kartu aksesmu<br><em>tidak berlaku di sini.</em></h1>
        <p class="hero-sub mx-auto">
            {{ $exception->getMessage() ?: 'Kamu tidak punya izin untuk mengakses halaman ini. Kalau menurutmu ini keliru, hubungi Super Admin.' }}
        </p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-brass">Kembali ke Dashboard</a>
            <a href="{{ route('guest.tentang') }}" class="btn btn-outline-cream">Ke Beranda</a>
        </div>
    </div>
</div>
</body>
</html>
