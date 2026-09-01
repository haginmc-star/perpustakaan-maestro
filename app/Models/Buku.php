<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'penulis', 'kategori', 'tahun_terbit', 'stok', 'stok_tersedia', 'sinopsis', 'cover',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function isTersedia(): bool
    {
        return $this->stok_tersedia > 0;
    }

    // URL cover, fallback null kalau belum ada gambar (nanti ditampilkan placeholder di view)
    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover ? \Illuminate\Support\Facades\Storage::url($this->cover) : null;
    }
}
