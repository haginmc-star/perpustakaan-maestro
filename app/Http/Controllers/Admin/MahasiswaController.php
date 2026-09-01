<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        if ($request->filled('cari')) {
            $cari = $request->input('cari');
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('nim', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $mahasiswas = $query->orderBy('nama')->paginate(15)->withQueryString();

        // pastikan status yang sudah lewat masa bekuan otomatis kebuka
        $mahasiswas->getCollection()->each->cekDanBukaBekuanOtomatis();

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Mahasiswa::create($data);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    // Riwayat semua peminjaman + hitung total keterlambatan untuk 1 mahasiswa
    public function riwayat(Mahasiswa $mahasiswa)
    {
        $riwayat = $mahasiswa->peminjamans()->with('buku')->orderByDesc('tanggal_pinjam')->paginate(15);
        $totalTerlambat = $mahasiswa->peminjamans()->where('status', 'terlambat')->count();

        return view('admin.mahasiswa.riwayat', compact('mahasiswa', 'riwayat', 'totalTerlambat'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $mahasiswa->update($data);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        abort_if(auth()->user()->role !== 'super_admin', 403, 'Hanya Super Admin yang bisa menghapus data mahasiswa.');

        \App\Models\ActivityLog::catat('Menghapus Mahasiswa', "Menghapus data {$mahasiswa->nama} ({$mahasiswa->nim})");
        $mahasiswa->delete();
        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    // Admin membekukan mahasiswa secara manual (misal karena pelanggaran lain)
    public function bekukan(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate(['alasan' => 'nullable|string|max:255', 'hari' => 'nullable|integer|min:1']);
        $mahasiswa->bekukanManual($request->input('alasan'), $request->input('hari'));

        \App\Models\ActivityLog::catat('Membekukan Mahasiswa (Manual)', $mahasiswa->nama . ' (' . $mahasiswa->nim . ') - alasan: ' . $request->input('alasan', '-'));

        return back()->with('success', "{$mahasiswa->nama} berhasil dibekukan.");
    }

    // Admin membuka bekuan secara manual (misal karena kesalahan input)
    public function aktifkan(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate(['alasan' => 'nullable|string|max:255']);
        $mahasiswa->aktifkanManual($request->input('alasan'));

        \App\Models\ActivityLog::catat('Mengaktifkan Mahasiswa (Manual)', $mahasiswa->nama . ' (' . $mahasiswa->nim . ') - alasan: ' . $request->input('alasan', '-'));

        return back()->with('success', "{$mahasiswa->nama} berhasil diaktifkan kembali.");
    }
}
