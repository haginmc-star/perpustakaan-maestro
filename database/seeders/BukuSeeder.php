<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['judul' => 'Algoritma dan Pemrograman', 'penulis' => 'Rinaldi Munir', 'kategori' => 'Teknik Informatika', 'tahun_terbit' => 2019, 'stok' => 3, 'sinopsis' => 'Dasar-dasar algoritma dan struktur pemrograman untuk pemula.', 'cover' => 'covers/algoritma-dan-pemrograman.svg'],
            ['judul' => 'Pengantar Manajemen', 'penulis' => 'James A. F. Stoner', 'kategori' => 'Manajemen', 'tahun_terbit' => 2018, 'stok' => 2, 'sinopsis' => 'Konsep dasar manajemen organisasi dan bisnis.', 'cover' => 'covers/pengantar-manajemen.svg'],
            ['judul' => 'Akuntansi Dasar', 'penulis' => 'Soemarso S.R.', 'kategori' => 'Akuntansi', 'tahun_terbit' => 2020, 'stok' => 2, 'sinopsis' => 'Prinsip-prinsip akuntansi dan pencatatan keuangan.', 'cover' => 'covers/akuntansi-dasar.svg'],
            ['judul' => 'Pengantar Ilmu Hukum', 'penulis' => 'C.S.T. Kansil', 'kategori' => 'Hukum', 'tahun_terbit' => 2017, 'stok' => 1, 'sinopsis' => 'Dasar-dasar sistem hukum dan tata negara Indonesia.', 'cover' => 'covers/pengantar-ilmu-hukum.svg'],
            ['judul' => 'Mekanika Struktur', 'penulis' => 'R.C. Hibbeler', 'kategori' => 'Teknik Sipil', 'tahun_terbit' => 2021, 'stok' => 2, 'sinopsis' => 'Analisis kekuatan dan kestabilan struktur bangunan.', 'cover' => 'covers/mekanika-struktur.svg'],
            ['judul' => 'Psikologi Umum', 'penulis' => 'Bimo Walgito', 'kategori' => 'Psikologi', 'tahun_terbit' => 2016, 'stok' => 1, 'sinopsis' => 'Konsep dasar perilaku dan proses mental manusia.', 'cover' => 'covers/psikologi-umum.svg'],
            ['judul' => 'Struktur Data', 'penulis' => 'Adam Drozdek', 'kategori' => 'Teknik Informatika', 'tahun_terbit' => 2022, 'stok' => 3, 'sinopsis' => 'Implementasi struktur data untuk efisiensi program.', 'cover' => 'covers/struktur-data.svg'],
            ['judul' => 'Dasar-Dasar Komunikasi', 'penulis' => 'Onong Uchjana', 'kategori' => 'Ilmu Komunikasi', 'tahun_terbit' => 2015, 'stok' => 2, 'sinopsis' => 'Teori dan praktik komunikasi massa dan interpersonal.', 'cover' => 'covers/dasar-dasar-komunikasi.svg'],
            ['judul' => 'Ekonomi Makro', 'penulis' => 'N. Gregory Mankiw', 'kategori' => 'Ekonomi Pembangunan', 'tahun_terbit' => 2020, 'stok' => 2, 'sinopsis' => 'Analisis kebijakan ekonomi dan pertumbuhan nasional.', 'cover' => 'covers/ekonomi-makro.svg'],
            ['judul' => 'English Grammar in Use', 'penulis' => 'Raymond Murphy', 'kategori' => 'Pendidikan Bahasa Inggris', 'tahun_terbit' => 2019, 'stok' => 1, 'sinopsis' => 'Panduan lengkap tata bahasa Inggris untuk pembelajar.', 'cover' => 'covers/english-grammar-in-use.svg'],
        ];

        foreach ($data as $row) {
            Buku::updateOrCreate(
                ['judul' => $row['judul']],
                $row + ['stok_tersedia' => $row['stok']]
            );
        }
    }
}
