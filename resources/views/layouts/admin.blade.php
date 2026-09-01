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

{{-- Top bar cuma muncul di HP/tablet (di bawah lg), berisi tombol buka menu --}}
<nav class="navbar sidebar-library d-lg-none px-3">
    <button class="btn btn-outline-cream btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile" aria-controls="sidebarMobile">
        ☰ Menu
    </button>
    <span class="text-white ms-2">📖 Admin Panel</span>
</nav>

@php
    $navItems = [
        ['route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard'), 'label' => 'Dashboard'],
        ['route' => 'admin.kalender.index', 'active' => request()->routeIs('admin.kalender.*'), 'label' => 'Kalender Jatuh Tempo'],
        ['route' => 'admin.buku.index', 'active' => request()->routeIs('admin.buku.*'), 'label' => 'Data Buku'],
        ['route' => 'admin.mahasiswa.index', 'active' => request()->routeIs('admin.mahasiswa.*'), 'label' => 'Data Mahasiswa'],
        ['route' => 'admin.peminjaman.index', 'active' => request()->routeIs('admin.peminjaman.*'), 'label' => 'Peminjaman'],
        ['route' => 'admin.peminjaman.create', 'active' => request()->routeIs('admin.peminjaman.create'), 'label' => '+ Pinjam Buku Baru'],
    ];
@endphp

{{-- Isi menu dipakai bareng buat sidebar desktop maupun offcanvas mobile --}}
@php
ob_start();
@endphp
<ul class="nav nav-pills flex-column gap-1">
    @foreach($navItems as $item)
        <li class="nav-item"><a class="nav-link {{ $item['active'] ? 'active' : '' }}" href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>
    @endforeach
    @if(auth()->user()->role === 'super_admin')
        <li class="mt-3 mb-1 px-2 small text-uppercase" style="color: rgba(248,246,238,0.45); letter-spacing:0.06em;">Super Admin</li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}" href="{{ route('admin.pengguna.index') }}">Kelola Akun Admin</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.aktivitas') ? 'active' : '' }}" href="{{ route('admin.aktivitas') }}">Log Aktivitas</a></li>
    @endif
</ul>
@php
$navMenuHtml = ob_get_clean();
@endphp

{{-- Offcanvas untuk HP/tablet --}}
<div class="offcanvas offcanvas-start sidebar-library text-white d-lg-none" tabindex="-1" id="sidebarMobile">
    <div class="offcanvas-header">
        <h5 class="mb-0">📖 Admin Panel</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="small mb-3" style="color: rgba(248,246,238,0.6);">
            {{ auth()->user()->name }}
            <span class="badge rounded-pill {{ auth()->user()->role === 'super_admin' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                {{ auth()->user()->role === 'super_admin' ? 'Super Admin' : 'Staff' }}
            </span>
        </div>
        {!! $navMenuHtml !!}
        <hr class="border-secondary">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-cream btn-sm w-100">Logout</button>
        </form>
    </div>
</div>

<div class="d-flex">
    {{-- Sidebar tetap, cuma tampil di layar besar (lg ke atas) --}}
    <div class="sidebar-library text-white p-3 d-none d-lg-block" style="width: 240px; min-height: 100vh; position: sticky; top:0;">
        <h5 class="mb-1">📖 Admin Panel</h5>
        <div class="small mb-4" style="color: rgba(248,246,238,0.6);">
            {{ auth()->user()->name }}
            <span class="badge rounded-pill {{ auth()->user()->role === 'super_admin' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                {{ auth()->user()->role === 'super_admin' ? 'Super Admin' : 'Staff' }}
            </span>
        </div>
        {!! $navMenuHtml !!}
        <hr class="border-secondary">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-cream btn-sm w-100">Logout</button>
        </form>
    </div>

    <div class="flex-grow-1 p-3 p-lg-4" style="min-width: 0;">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
