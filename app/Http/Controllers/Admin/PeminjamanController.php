<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    const LAMA_PINJAM_HARI = 7;      // durasi peminjaman
    const LAMA_BEKUAN_HARI = 3;      // durasi pembekuan otomatis kalau telat

    public function index(Request $request)
    {
        $query = Peminjaman::with(['mahasiswa', 'buku']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('cari')) {
            $cari = $request->input('cari');
            $query->whereHas('mahasiswa', function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")->orWhere('nim', 'like', "%{$cari}%");
            })->orWhereHas('buku', function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%");
            });
        }

        $peminjamans = $query->orderByDesc('tanggal_pinjam')->paginate(15)->withQueryString();

        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $bukus = Buku::where('stok_tersedia', '>', 0)->orderBy('judul')->get();

        return view('admin.peminjaman.create', compact('mahasiswas', 'bukus'));
    }

    // Proses input peminjaman baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($data['mahasiswa_id']);
        $buku = Buku::findOrFail($data['buku_id']);

        // Validasi bisnis: mahasiswa tidak boleh sedang dibekukan
        if (!$mahasiswa->bisaMeminjam()) {
            return back()->withErrors([
                'mahasiswa_id' => "{$mahasiswa->nama} sedang dibekukan sampai {$mahasiswa->tanggal_selesai_bekuan?->format('d-m-Y')} dan tidak bisa meminjam buku.",
            ])->withInput();
        }

        // Validasi bisnis: stok buku harus tersedia
        if (!$buku->isTersedia()) {
            return back()->withErrors(['buku_id' => 'Stok buku ini sedang habis / semua sedang dipinjam.'])->withInput();
        }

        DB::transaction(function () use ($mahasiswa, $buku) {
            Peminjaman::create([
                'mahasiswa_id' => $mahasiswa->id,
                'buku_id' => $buku->id,
                'tanggal_pinjam' => Carbon::today(),
                'tanggal_jatuh_tempo' => Carbon::today()->addDays(self::LAMA_PINJAM_HARI),
                'status' => 'dipinjam',
            ]);

            $buku->decrement('stok_tersedia');
        });

        \App\Models\ActivityLog::catat('Peminjaman Baru', $mahasiswa->nama . ' meminjam "' . $buku->judul . '"');

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    // Proses pengembalian buku. Kalau telat, mahasiswa otomatis dibekukan.
    public function kembalikan(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->tanggal_kembali) {
            return back()->with('info', 'Peminjaman ini sudah pernah ditandai dikembalikan.');
        }

        $terlambat = Carbon::today()->greaterThan($peminjaman->tanggal_jatuh_tempo);

        DB::transaction(function () use ($peminjaman, $terlambat) {
            $peminjaman->update([
                'tanggal_kembali' => Carbon::today(),
                'status' => $terlambat ? 'terlambat' : 'dikembalikan',
            ]);

            $peminjaman->buku->increment('stok_tersedia');

            if ($terlambat) {
                $peminjaman->mahasiswa->bekukanOtomatis(self::LAMA_BEKUAN_HARI);
            }
        });

        \App\Models\ActivityLog::catat(
            $terlambat ? 'Pengembalian Terlambat' : 'Pengembalian Buku',
            $peminjaman->mahasiswa->nama . ' mengembalikan "' . $peminjaman->buku->judul . '"'
        );

        $pesan = $terlambat
            ? 'Buku dikembalikan (TERLAMBAT). Mahasiswa otomatis dibekukan selama ' . self::LAMA_BEKUAN_HARI . ' hari.'
            : 'Buku berhasil dikembalikan tepat waktu.';

        return back()->with($terlambat ? 'warning' : 'success', $pesan);
    }

    // Perpanjang masa peminjaman: hanya boleh sebelum jatuh tempo, maksimal 2 kali, tiap kali +7 hari
    public function perpanjang(Peminjaman $peminjaman)
    {
        if (!$peminjaman->bisaDiperpanjang()) {
            return back()->withErrors([
                'perpanjang' => 'Peminjaman ini tidak bisa diperpanjang lagi (sudah terlambat, sudah dikembalikan, atau sudah mencapai batas maksimal 2x perpanjangan).',
            ]);
        }

        $peminjaman->perpanjang();

        \App\Models\ActivityLog::catat('Perpanjang Peminjaman', $peminjaman->mahasiswa->nama . ' memperpanjang "' . $peminjaman->buku->judul . '" (ke-' . $peminjaman->jumlah_perpanjangan . ')');

        return back()->with('success', "Peminjaman berhasil diperpanjang 7 hari. Jatuh tempo baru: {$peminjaman->tanggal_jatuh_tempo->format('d-m-Y')} (perpanjangan ke-{$peminjaman->jumlah_perpanjangan}).");
    }
}
