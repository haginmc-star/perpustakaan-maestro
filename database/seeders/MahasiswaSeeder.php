<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nim' => '2201001', 'nama' => 'Ahmad Fauzi', 'jurusan' => 'Teknik Informatika', 'no_hp' => '081234560001'],
            ['nim' => '2201002', 'nama' => 'Siti Nurhaliza', 'jurusan' => 'Manajemen', 'no_hp' => '081234560002'],
            ['nim' => '2201003', 'nama' => 'Budi Santoso', 'jurusan' => 'Akuntansi', 'no_hp' => '081234560003'],
            ['nim' => '2201004', 'nama' => 'Dewi Lestari', 'jurusan' => 'Hukum', 'no_hp' => '081234560004'],
            ['nim' => '2201005', 'nama' => 'Rizky Ramadhan', 'jurusan' => 'Teknik Sipil', 'no_hp' => '081234560005'],
            ['nim' => '2201006', 'nama' => 'Putri Ayu Wulandari', 'jurusan' => 'Psikologi', 'no_hp' => '081234560006'],
            ['nim' => '2201007', 'nama' => 'Fajar Nugroho', 'jurusan' => 'Teknik Informatika', 'no_hp' => '081234560007'],
            ['nim' => '2201008', 'nama' => 'Anisa Rahmawati', 'jurusan' => 'Ilmu Komunikasi', 'no_hp' => '081234560008'],
            ['nim' => '2201009', 'nama' => 'Muhammad Iqbal', 'jurusan' => 'Ekonomi Pembangunan', 'no_hp' => '081234560009'],
            ['nim' => '2201010', 'nama' => 'Nadia Kusuma', 'jurusan' => 'Pendidikan Bahasa Inggris', 'no_hp' => '081234560010'],
        ];

        foreach ($data as $row) {
            Mahasiswa::updateOrCreate(['nim' => $row['nim']], $row + ['status' => 'aktif']);
        }
    }
}
