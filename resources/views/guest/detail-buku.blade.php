@extends('layouts.guest')
@section('title', $buku->judul)

@section('content')
<a href="{{ route('guest.katalog') }}" class="text-decoration-none small">&larr; Kembali ke Katalog</a>

<div class="row mt-3 g-4">
    <div class="col-md-4">
        @if($buku->cover_url)
            <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="w-100 rounded shadow-sm" style="aspect-ratio: 3/4; object-fit: cover;">
        @else
            <div class="w-100 rounded bg-white border d-flex align-items-center justify-content-center text-muted" style="aspect-ratio: 3/4;">
                Tanpa Cover
            </div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="section-eyebrow">{{ $buku->kategori ?? 'Umum' }} &middot; {{ $buku->tahun_terbit }}</div>
        <h1 class="mb-1">{{ $buku->judul }}</h1>
        <p class="text-muted fs-5 mb-3">{{ $buku->penulis ?? 'Penulis tidak diketahui' }}</p>

        @if($buku->isTersedia())
            <span class="badge rounded-pill bg-success mb-3">Tersedia &middot; {{ $buku->stok_tersedia }} dari {{ $buku->stok }} eksemplar</span>
        @else
            <span class="badge rounded-pill bg-secondary mb-3">Sedang Dipinjam Semua</span>
        @endif

        <h5 class="mt-3">Sinopsis</h5>
        <p>{{ $buku->sinopsis ?? 'Belum ada sinopsis untuk buku ini.' }}</p>

        <div class="catalog-card mt-4" style="max-width: 420px;">
            <div class="catalog-code">Cara Meminjam</div>
            <p class="small mb-0">
                Buku fisik bisa dipinjam langsung di perpustakaan kampus. Tunjukkan KTM kamu
                ke petugas untuk mulai proses peminjaman.
            </p>
        </div>
    </div>
</div>
@endsection
