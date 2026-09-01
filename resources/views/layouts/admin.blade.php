<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin - Perpustakaan')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <div class="sidebar-library text-white vh-100 p-3" style="width: 240px; position: sticky; top:0;">
        <h5 class="mb-1">📖 Admin Panel</h5>
        <div class="small mb-4" style="color: rgba(248,246,238,0.6);">
            {{ auth()->user()->name }}
            <span class="badge rounded-pill {{ auth()->user()->role === 'super_admin' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                {{ auth()->user()->role === 'super_admin' ? 'Super Admin' : 'Staff' }}
            </span>
        </div>
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.kalender.*') ? 'active' : '' }}" href="{{ route('admin.kalender.index') }}">Kalender Jatuh Tempo</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.buku.*') ? 'active' : '' }}" href="{{ route('admin.buku.index') }}">Data Buku</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}" href="{{ route('admin.mahasiswa.index') }}">Data Mahasiswa</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}" href="{{ route('admin.peminjaman.index') }}">Peminjaman</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.peminjaman.create') ? 'active' : '' }}" href="{{ route('admin.peminjaman.create') }}">+ Pinjam Buku Baru</a></li>
            @if(auth()->user()->role === 'super_admin')
                <li class="mt-3 mb-1 px-2 small text-uppercase" style="color: rgba(248,246,238,0.45); letter-spacing:0.06em;">Super Admin</li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}" href="{{ route('admin.pengguna.index') }}">Kelola Akun Admin</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.aktivitas') ? 'active' : '' }}" href="{{ route('admin.aktivitas') }}">Log Aktivitas</a></li>
            @endif
        </ul>
        <hr class="border-secondary">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-cream btn-sm w-100">Logout</button>
        </form>
    </div>

    <div class="flex-grow-1 p-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>
@stack('scripts')
</body>
</html>
