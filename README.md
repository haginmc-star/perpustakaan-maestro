# Sistem Perpustakaan Universitas (Laravel)

Paket ini berisi **file aplikasi** (migration, model, controller, route, view) sesuai
kebutuhan yang sudah kita diskusikan. Karena environment saya tidak bisa mengunduh
package Laravel dari Packagist, kamu perlu membuat project Laravel kosong terlebih
dahulu di komputermu, lalu menyalin file-file ini ke dalamnya.

## 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel perpustakaan
cd perpustakaan
```

(Pastikan sudah install PHP >= 8.2, Composer, dan MySQL/SQLite di komputermu.)

## 2. Salin file dari paket ini

Salin folder & file berikut, timpa (replace) file yang sudah ada di project Laravel barumu:

- `app/Models/Mahasiswa.php`
- `app/Models/Buku.php`
- `app/Models/Peminjaman.php`
- `app/Http/Controllers/Auth/AdminLoginController.php`
- `app/Http/Controllers/GuestController.php`
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/MahasiswaController.php`
- `app/Http/Controllers/Admin/BukuController.php`
- `app/Http/Controllers/Admin/PeminjamanController.php`
- `app/Http/Middleware/IsAdmin.php`
- `app/Console/Commands/CekBekuanMahasiswa.php`
- `database/migrations/*` (4 file baru)
- `database/seeders/AdminSeeder.php`
- `routes/web.php` (timpa yang lama)
- `resources/views/*` (semua folder: layouts, guest, auth, admin)
- `bootstrap/app.php` **HANYA jika kamu pakai Laravel 11+**.
  Kalau kamu pakai **Laravel 10 ke bawah**, JANGAN timpa file ini. Sebagai gantinya:
  - Daftarkan middleware di `app/Http/Kernel.php`, di `$routeMiddleware`:
    ```php
    'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ```
  - Daftarkan scheduler di `app/Console/Kernel.php`, di method `schedule()`:
    ```php
    $schedule->command(\App\Console\Commands\CekBekuanMahasiswa::class)->dailyAt('00:05');
    ```

## 3. Atur koneksi database

Edit file `.env`, sesuaikan dengan database kamu (contoh pakai MySQL):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

## 4. Jalankan migration & seeder

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

Ini akan membuat akun admin default:
- **Email:** admin@perpustakaan.test
- **Password:** password123

⚠️ **Segera ganti password ini setelah login pertama kali.**

## 5. Jalankan server

```bash
php artisan serve
```

Buka:
- `http://localhost:8000/` → halaman publik (tentang & katalog)
- `http://localhost:8000/admin/login` → login admin
- `http://localhost:8000/admin/dashboard` → dashboard admin (setelah login)

## 6. (Opsional) Aktifkan scheduler otomatis

Supaya status "dibekukan" otomatis kebuka setelah 3 hari meskipun tidak ada
admin yang membuka dashboard, jalankan scheduler Laravel via cron (di server produksi):

```
* * * * * cd /path-ke-project && php artisan schedule:run >> /dev/null 2>&1
```

Catatan: sistem ini **juga** sudah mengecek otomatis setiap kali dashboard/halaman
mahasiswa dibuka, jadi tanpa cron pun tetap akan terupdate saat admin login dan membuka halaman.

## Update Terbaru: Export PDF Rekap Bulanan/Tahunan + Cover Buku Otomatis

**WAJIB dijalankan sebelum fitur PDF bisa jalan** (cukup 1 kali):

```bash
composer require barryvdh/laravel-dompdf
```

Perintah ini butuh koneksi internet ke Packagist, dan harus dijalankan di **komputer kamu**
(bukan di sandbox saya), karena package ini yang menyediakan kemampuan generate PDF di Laravel.
Setelah command ini selesai, restart `php artisan serve` kalau sedang berjalan.

Tanpa `composer require` ini, halaman Dashboard akan error "Class Pdf not found" saat diklik
tombol Export PDF.

**Fitur baru:**
- **Export PDF Bulanan**: tombol di Dashboard, generate PDF berisi ringkasan + detail transaksi
  bulan yang sedang dipilih.
- **Export PDF Tahunan**: tombol di Dashboard, generate PDF berisi rincian per bulan (Januari–
  Desember) untuk 1 tahun penuh, plus total keseluruhan.
- **Cover Buku Otomatis untuk 10 Data Dummy**: 10 buku dari seeder sekarang otomatis punya cover
  (didesain dengan gaya yang sama seperti web-nya). Kalau kamu jalankan ulang `php artisan
  db:seed --class=BukuSeeder`, cover-nya akan ikut ter-update.

## Update Terbaru: Multi-Level Admin, Cover Buku, Riwayat

Setelah menimpa file-file baru ini, ada 2 langkah tambahan yang WAJIB dijalankan:

```bash
php artisan migrate
php artisan storage:link
```

`storage:link` **wajib** dijalankan (cukup 1 kali saja), supaya file cover buku yang diupload
bisa diakses lewat browser. Tanpa ini, gambar cover tidak akan muncul (broken image).

**Fitur baru:**
- **Multi-Level Admin**: role `super_admin` (akses penuh + kelola akun admin lain + lihat log
  aktivitas) dan `staff` (bisa proses peminjaman/pengembalian/perpanjangan, TAPI tidak bisa
  menghapus data buku/mahasiswa atau mengelola akun admin). Akun default dari AdminSeeder
  otomatis jadi `super_admin`.
- **Cover Buku**: saat tambah/edit buku, bisa upload gambar cover (opsional, maks 2MB). Tampil
  di katalog publik dan halaman admin.
- **Riwayat**: klik "Riwayat" di tabel Data Buku untuk lihat semua peminjam buku itu. Klik
  "Riwayat" di tabel Data Mahasiswa untuk lihat semua riwayat peminjaman + total keterlambatan
  mahasiswa tersebut.
- **Log Aktivitas** (khusus super_admin): mencatat semua aksi penting (tambah/hapus buku,
  bekukan mahasiswa, peminjaman, dll) beserta siapa yang melakukannya.

## Ringkasan Alur Bisnis yang Sudah Diimplementasikan

1. **Guest** (tanpa login): hanya bisa lihat halaman "Tentang/Sejarah" dan "Katalog Buku"
   (dengan status tersedia/dipinjam), tidak bisa akses fitur admin.
2. **Admin** (login): kelola data buku, mahasiswa, dan transaksi peminjaman/pengembalian.
3. **Peminjaman**: admin pilih mahasiswa + buku → sistem catat tanggal pinjam & jatuh
   tempo otomatis (7 hari), stok buku otomatis berkurang.
4. **Pengembalian**: admin klik "Kembalikan" → sistem cek apakah terlambat.
   - Kalau **tidak** terlambat → status "Dikembalikan", stok buku kembali bertambah.
   - Kalau **terlambat** → status "Terlambat", stok buku tetap bertambah, DAN mahasiswa
     **otomatis dibekukan 3 hari** (tidak bisa pinjam buku baru selama masa itu).
5. **Admin bisa membekukan/mengaktifkan mahasiswa secara manual** kapan saja (misalnya
   ada kesalahan input), lengkap dengan catatan alasan.
6. **Dashboard admin** menampilkan rekap: total buku, buku tersedia, sedang dipinjam,
   mahasiswa dibekukan, serta rekap bulanan (jumlah peminjam/pengembalian/terlambat
   per bulan, bisa difilter per bulan & tahun) dan daftar peminjaman aktif.

## Yang Belum Dibuat (bisa dikembangkan lagi nanti)
- Export laporan ke Excel/PDF
- Grafik/chart visual untuk rekap bulanan
- Multi-eksemplar per buku dengan nomor rak/kode barcode
- Notifikasi email/WhatsApp saat jatuh tempo mendekat
