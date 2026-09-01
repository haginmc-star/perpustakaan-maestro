<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    // Halaman tentang / sejarah perpustakaan (statis)
    public function tentang()
    {
        return view('guest.tentang');
    }

    // Katalog buku yang tersedia untuk dipinjam, bisa cari & filter
    public function katalog(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('cari')) {
            $cari = $request->input('cari');
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        if ($request->boolean('hanya_tersedia')) {
            $query->where('stok_tersedia', '>', 0);
        }

        $bukus = $query->orderBy('judul')->paginate(12)->withQueryString();
        $kategoriList = Buku::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        return view('guest.katalog', compact('bukus', 'kategoriList'));
    }

    // Halaman detail 1 buku untuk pengunjung (cover, penulis, sinopsis, status)
    public function detailBuku(Buku $buku)
    {
        return view('guest.detail-buku', compact('buku'));
    }

    // Mahasiswa cek status peminjamannya sendiri pakai NIM, tanpa perlu login
    public function cekPeminjaman(Request $request)
    {
        $mahasiswa = null;
        $peminjamans = collect();

        if ($request->filled('nim')) {
            $mahasiswa = \App\Models\Mahasiswa::where('nim', $request->input('nim'))->first();

            if ($mahasiswa) {
                $mahasiswa->cekDanBukaBekuanOtomatis();
                $peminjamans = $mahasiswa->peminjamans()
                    ->with('buku')
                    ->orderByDesc('tanggal_pinjam')
                    ->get();
            }
        }

        return view('guest.cek-peminjaman', compact('mahasiswa', 'peminjamans'));
    }
}
