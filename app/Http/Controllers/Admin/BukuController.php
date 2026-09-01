<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('cari')) {
            $cari = $request->input('cari');
            $query->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
        }

        $bukus = $query->orderBy('judul')->paginate(15)->withQueryString();

        return view('admin.buku.index', compact('bukus'));
    }

    public function create()
    {
        return view('admin.buku.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|digits:4',
            'stok' => 'required|integer|min:1',
            'sinopsis' => 'nullable|string',
            'cover' => 'nullable|image|max:2048', // max 2MB
        ]);

        $data['stok_tersedia'] = $data['stok']; // saat baru ditambahkan, semua stok tersedia

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($data);

        \App\Models\ActivityLog::catat('Menambah Buku', "Menambahkan buku \"{$data['judul']}\"");

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('admin.buku.edit', compact('buku'));
    }

    // Riwayat semua peminjaman untuk 1 buku tertentu
    public function riwayat(Buku $buku)
    {
        $riwayat = $buku->peminjamans()->with('mahasiswa')->orderByDesc('tanggal_pinjam')->paginate(15);
        return view('admin.buku.riwayat', compact('buku', 'riwayat'));
    }

    public function update(Request $request, Buku $buku)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|digits:4',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        // Sesuaikan stok_tersedia mengikuti perubahan stok total,
        // tanpa mengubah jumlah yang sedang dipinjam.
        $sedangDipinjam = $buku->stok - $buku->stok_tersedia;
        $data['stok_tersedia'] = max(0, $data['stok'] - $sedangDipinjam);

        if ($request->hasFile('cover')) {
            // Hapus cover lama kalau ada, biar tidak menumpuk file tak terpakai
            if ($buku->cover) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        abort_if(auth()->user()->role !== 'super_admin', 403, 'Hanya Super Admin yang bisa menghapus data buku.');

        \App\Models\ActivityLog::catat('Menghapus Buku', "Menghapus buku \"{$buku->judul}\"");
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
