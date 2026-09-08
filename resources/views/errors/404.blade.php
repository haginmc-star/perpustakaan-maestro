<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 - Halaman Tidak Ditemukan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="hero-library d-flex align-items-center" style="min-height: 100vh;">
    <div class="container text-center position-relative">
        <div class="hero-eyebrow">Perpustakaan Universitas &middot; Error 404</div>
        <h1 class="hero-title">Rak ini <em>kosong</em>,<br>halaman tidak ditemukan.</h1>
        <p class="hero-sub mx-auto">
            Sepertinya halaman atau buku yang kamu cari sudah dipindahkan, dihapus,
            atau memang belum pernah ada di katalog kami.
        </p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ route('guest.tentang') }}" class="btn btn-brass">Kembali ke Beranda</a>
            <a href="{{ route('guest.katalog') }}" class="btn btn-outline-cream">Lihat Katalog Buku</a>
        </div>
    </div>
</div>
</body>
</html>
