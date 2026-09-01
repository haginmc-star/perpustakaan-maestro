@extends('layouts.guest')
@section('title', 'Katalog Buku')

@section('content')
<div class="section-eyebrow">Koleksi Perpustakaan</div>
<h2 class="mb-4">Katalog Buku</h2>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari judul / penulis...">
    </div>
    <div class="col-md-3">
        <select name="kategori" class="form-select">
            <option value="">-- Semua Kategori --</option>
            @foreach($kategoriList as $kategori)
                <option value="{{ $kategori }}" @selected(request('kategori') == $kategori)>{{ $kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="hanya_tersedia" value="1" id="hanyaTersedia" @checked(request('hanya_tersedia'))>
            <label class="form-check-label" for="hanyaTersedia">Hanya yang tersedia</label>
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Cari</button>
    </div>
</form>

@php
    // Warna aksen kartu ditentukan dari kategori, biar tiap kategori punya "warna punggung buku" sendiri
    $paletteKategori = ['#3A5C46', '#B58A3D', '#8B3A3A', '#4A4A42', '#24402F', '#9C7530'];
@endphp

<div class="row row-cols-1 row-cols-md-3 g-3">
    @forelse($bukus as $i => $buku)
        @php $warna = $paletteKategori[crc32($buku->kategori ?? 'lain') % count($paletteKategori)]; @endphp
        <div class="col">
            <a href="{{ route('guest.detail-buku', $buku) }}" class="text-decoration-none text-reset">
            <div class="catalog-card h-100" style="--cat-color: {{ $warna }}">
                @if($buku->cover_url)
                    <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="mb-2 rounded" style="width:100%; height:160px; object-fit:cover;">
                @endif
                <div class="catalog-code">{{ $buku->kategori ?? 'UMUM' }} &middot; {{ $buku->tahun_terbit }}</div>
                <h5>{{ $buku->judul }}</h5>
                <div class="text-muted small mb-2">{{ $buku->penulis ?? '-' }}</div>
                @if($buku->isTersedia())
                    <span class="badge bg-success">Tersedia &middot; {{ $buku->stok_tersedia }}</span>
                @else
                    <span class="badge bg-secondary">Sedang Dipinjam</span>
                @endif
            </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p class="mb-0">Buku tidak ditemukan. Coba kata kunci lain.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $bukus->links() }}
</div>
@endsection
