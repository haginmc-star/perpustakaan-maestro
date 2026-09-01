<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        // Ambil semua peminjaman yang jatuh temponya di bulan ini (yang belum dikembalikan)
        $peminjamans = Peminjaman::with(['mahasiswa', 'buku'])
            ->whereNull('tanggal_kembali')
            ->whereBetween('tanggal_jatuh_tempo', [$awalBulan, $akhirBulan])
            ->orderBy('tanggal_jatuh_tempo')
            ->get();

        // Kelompokkan per tanggal (format Y-m-d sebagai key), supaya gampang dipetakan ke kalender
        $perTanggal = $peminjamans->groupBy(fn ($p) => $p->tanggal_jatuh_tempo->format('Y-m-d'));

        // Bangun array tanggal untuk grid kalender (termasuk padding hari sebelumnya biar rapi 7 kolom)
        $tanggalMulaiGrid = $awalBulan->copy()->startOfWeek(Carbon::SUNDAY);
        $tanggalAkhirGrid = $akhirBulan->copy()->endOfWeek(Carbon::SATURDAY);

        $hariGrid = [];
        $cursor = $tanggalMulaiGrid->copy();
        while ($cursor->lte($tanggalAkhirGrid)) {
            $hariGrid[] = $cursor->copy();
            $cursor->addDay();
        }

        return view('admin.kalender.index', compact(
            'bulan', 'tahun', 'awalBulan', 'hariGrid', 'perTanggal'
        ));
    }
}
