<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sedang Pemeliharaan - Perpustakaan Universitas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="hero-library d-flex align-items-center" style="min-height: 100vh;">
    <div class="container text-center position-relative">
        <div class="hero-eyebrow">Perpustakaan Universitas</div>
        <h1 class="hero-title">Sedang <em>ditata ulang</em><br>sebentar ya.</h1>
        <p class="hero-sub mx-auto">
            {{ $pesan ?? 'Web sedang dalam pemeliharaan. Silakan kembali lagi nanti.' }}
        </p>
        <a href="{{ route('admin.login') }}" class="btn btn-outline-cream mt-3">Login Admin</a>
    </div>
</div>
</body>
</html>
