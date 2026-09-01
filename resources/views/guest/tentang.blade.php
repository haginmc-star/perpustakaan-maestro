@extends('layouts.guest')
@section('title', 'Tentang Perpustakaan')

@section('hero')
<section class="hero-library">
    <div class="container position-relative">
        <div class="hero-eyebrow">Pusat Sumber Belajar &middot; Est. 1985</div>
        <h1 class="hero-title">
            Setiap rak menyimpan<br>
            <em>satu jalan menuju tahu.</em>
        </h1>
        <p class="hero-sub">
            Perpustakaan Universitas melayani seluruh civitas akademika dengan koleksi
            buku, jurnal, dan referensi yang terus diperbarui — ditata rapi, mudah dicari,
            dan selalu terbuka untuk dijelajahi.
        </p>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('guest.katalog') }}" class="btn btn-brass">Lihat Katalog Buku</a>
            <a href="{{ route('guest.cek-peminjaman') }}" class="btn btn-outline-cream">Cek Status Peminjaman</a>
        </div>

        <div class="hero-stats">
            <div>
                <div class="hero-stat-num">10.000+</div>
                <div class="hero-stat-label">Judul Koleksi</div>
            </div>
            <div>
                <div class="hero-stat-num">08.00–16.00</div>
                <div class="hero-stat-label">Jam Layanan Sen–Jum</div>
            </div>
            <div>
                <div class="hero-stat-num">7 Hari</div>
                <div class="hero-stat-label">Masa Pinjam</div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="row g-5">
    <div class="col-lg-7">
        <div class="section-eyebrow">Sejarah Singkat</div>
        <h2 class="mb-3">Dari rak kayu ke katalog digital</h2>
        <p>
            Tuliskan di sini sejarah perpustakaan kampus kamu: kapan didirikan, perkembangan
            koleksi, renovasi gedung, digitalisasi katalog, dan pencapaian lainnya. Bagian ini
            murni konten statis, jadi tinggal disesuaikan dengan teks aslinya.
        </p>
        <p>
            Perpustakaan ini tumbuh bersama kampus — dari ruang baca kecil menjadi pusat
            literasi yang melayani ribuan mahasiswa setiap tahunnya, kini dengan katalog
            yang bisa diakses dari mana saja.
        </p>
    </div>
    <div class="col-lg-5">
        <div class="catalog-card" style="--cat-color: var(--forest-700)">
            <div class="catalog-code">VISI — 01</div>
            <h5>Visi</h5>
            <p class="mb-0 small">Menjadi pusat informasi dan literasi terdepan yang mendukung Tri Dharma Perguruan Tinggi.</p>
        </div>
        <div class="catalog-card mt-3" style="--cat-color: var(--brass-500)">
            <div class="catalog-code">MISI — 02</div>
            <h5>Misi</h5>
            <ul class="mb-0 small ps-3">
                <li>Menyediakan koleksi yang relevan dan mutakhir</li>
                <li>Layanan peminjaman yang cepat dan transparan</li>
                <li>Mendorong budaya literasi kampus</li>
            </ul>
        </div>
    </div>
</div>
@endsection
