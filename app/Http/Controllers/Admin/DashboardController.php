<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan status bekuan mahasiswa yang sudah lewat masanya diperbarui
        Mahasiswa::where('status', 'dibekukan')->get()->each->cekDanBukaBekuanOtomatis();

        // Auto update status peminjaman yang lewat jatuh tempo tapi belum ditandai terlambat
        Peminjaman::whereNull('tanggal_kembali')
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok_tersedia', '>', 0)->count();
        $sedangDipinjam = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $mahasiswaDibekukan = Mahasiswa::where('status', 'dibekukan')->count();

        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $rekap = $this->hitungRekapBulanan($bulan, $tahun);

        $peminjamanAktif = Peminjaman::with(['mahasiswa', 'buku'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->orderBy('tanggal_jatuh_tempo')
            ->get();

        // Data untuk grafik tren 12 bulan (tahun yang sedang difilter)
        $labelBulanChart = [];
        $dataPeminjamChart = [];
        $dataPengembalianChart = [];
        $dataTerlambatChart = [];
        for ($b = 1; $b <= 12; $b++) {
            $r = $this->hitungRekapBulanan($b, $tahun);
            $labelBulanChart[] = Carbon::create($tahun, $b, 1)->translatedFormat('M');
            $dataPeminjamChart[] = $r['jumlahPeminjamBulanIni'];
            $dataPengembalianChart[] = $r['jumlahPengembalianBulanIni'];
            $dataTerlambatChart[] = $r['jumlahTerlambatBulanIni'];
        }

        // Data untuk grafik kategori buku paling sering dipinjam (sepanjang waktu)
        $kategoriPopuler = Peminjaman::join('bukus', 'peminjamans.buku_id', '=', 'bukus.id')
            ->selectRaw('bukus.kategori, COUNT(*) as total')
            ->whereNotNull('bukus.kategori')
            ->groupBy('bukus.kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $labelKategoriChart = $kategoriPopuler->pluck('kategori');
        $dataKategoriChart = $kategoriPopuler->pluck('total');

        return view('admin.dashboard', array_merge($rekap, compact(
            'totalBuku', 'bukuTersedia', 'sedangDipinjam', 'mahasiswaDibekukan',
            'peminjamanAktif', 'bulan', 'tahun',
            'labelBulanChart', 'dataPeminjamChart', 'dataPengembalianChart', 'dataTerlambatChart',
            'labelKategoriChart', 'dataKategoriChart'
        )));
    }

    // Dipakai bareng oleh dashboard & export PDF, biar angkanya selalu konsisten
    private function hitungRekapBulanan(int $bulan, int $tahun): array
    {
        $jumlahPeminjamBulanIni = Peminjaman::whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)->count();

        $jumlahPengembalianBulanIni = Peminjaman::whereMonth('tanggal_kembali', $bulan)
            ->whereYear('tanggal_kembali', $tahun)->count();

        $jumlahTerlambatBulanIni = Peminjaman::whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->where(function ($q) {
                $q->where('status', 'terlambat')
                  ->orWhereColumn('tanggal_kembali', '>', 'tanggal_jatuh_tempo');
            })->count();

        return compact('jumlahPeminjamBulanIni', 'jumlahPengembalianBulanIni', 'jumlahTerlambatBulanIni');
    }

    // Export rekap 1 bulan tertentu ke PDF
    public function exportPdfBulanan(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $rekap = $this->hitungRekapBulanan($bulan, $tahun);

        $detail = Peminjaman::with(['mahasiswa', 'buku'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->orderBy('tanggal_pinjam')
            ->get();

        $namaBulan = Carbon::create($tahun, $bulan, 1)->translatedFormat('F');

        $pdf = Pdf::loadView('admin.dashboard.pdf-bulanan', array_merge($rekap, compact('detail', 'namaBulan', 'bulan', 'tahun')));

        return $pdf->download("rekap-{$namaBulan}-{$tahun}.pdf");
    }

    // Export rekap 1 tahun penuh (breakdown per bulan) ke PDF
    public function exportPdfTahunan(Request $request)
    {
        $tahun = (int) $request->input('tahun', now()->year);

        $breakdown = [];
        for ($b = 1; $b <= 12; $b++) {
            $rekapBulan = $this->hitungRekapBulanan($b, $tahun);
            $breakdown[] = array_merge($rekapBulan, [
                'bulan' => $b,
                'namaBulan' => Carbon::create($tahun, $b, 1)->translatedFormat('F'),
            ]);
        }

        $totalPeminjam = array_sum(array_column($breakdown, 'jumlahPeminjamBulanIni'));
        $totalPengembalian = array_sum(array_column($breakdown, 'jumlahPengembalianBulanIni'));
        $totalTerlambat = array_sum(array_column($breakdown, 'jumlahTerlambatBulanIni'));

        $pdf = Pdf::loadView('admin.dashboard.pdf-tahunan', compact('breakdown', 'tahun', 'totalPeminjam', 'totalPengembalian', 'totalTerlambat'));

        return $pdf->download("rekap-tahunan-{$tahun}.pdf");
    }
}
