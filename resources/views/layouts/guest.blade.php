<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Perpustakaan Universitas')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-library sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('guest.tentang') }}">📖 Perpustakaan Universitas</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <form method="GET" action="{{ route('guest.katalog') }}" class="d-flex ms-lg-3 my-2 my-lg-0" style="max-width: 280px;">
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control form-control-sm" placeholder="Cari judul buku...">
                </form>

                <div class="navbar-nav ms-lg-auto flex-lg-row gap-lg-1">
                    <a class="nav-link {{ request()->routeIs('guest.tentang') ? 'active' : '' }}" href="{{ route('guest.tentang') }}">Tentang</a>
                    <a class="nav-link {{ request()->routeIs('guest.katalog') || request()->routeIs('guest.detail-buku') ? 'active' : '' }}" href="{{ route('guest.katalog') }}">Katalog Buku</a>
                    <a class="nav-link {{ request()->routeIs('guest.cek-peminjaman') ? 'active' : '' }}" href="{{ route('guest.cek-peminjaman') }}">Cek Peminjaman</a>
                    <a class="nav-link" href="{{ route('admin.login') }}">Login Admin</a>
                </div>
            </div>
        </div>
    </nav>

    @yield('hero')

    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>

    <footer class="footer-library text-center">
        &copy; {{ date('Y') }} PERPUSTAKAAN UNIVERSITAS — KATALOG NO. 000-2026
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
