<?php

namespace App\Console\Commands;

use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CekBekuanMahasiswa extends Command
{
    protected $signature = 'mahasiswa:cek-bekuan';
    protected $description = 'Membuka status bekuan mahasiswa yang sudah lewat masa 3 hari, dan menandai peminjaman yang lewat jatuh tempo sebagai terlambat.';

    public function handle(): void
    {
        $dibuka = 0;
        Mahasiswa::where('status', 'dibekukan')->get()->each(function ($m) use (&$dibuka) {
            $statusSebelum = $m->status;
            $m->cekDanBukaBekuanOtomatis();
            if ($statusSebelum === 'dibekukan' && $m->fresh()->status === 'aktif') {
                $dibuka++;
            }
        });

        $ditandaiTerlambat = Peminjaman::whereNull('tanggal_kembali')
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        $this->info("Selesai. {$dibuka} mahasiswa dibuka bekuannya, {$ditandaiTerlambat} peminjaman ditandai terlambat.");
    }
}
