<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'mahasiswa_id', 'buku_id', 'tanggal_pinjam', 'tanggal_jatuh_tempo',
        'tanggal_kembali', 'status', 'jumlah_perpanjangan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function isTerlambat(): bool
    {
        $bandingan = $this->tanggal_kembali ?? Carbon::today();
        return $bandingan->greaterThan($this->tanggal_jatuh_tempo);
    }

    public function sisaHariAtauTerlambat(): int
    {
        // positif = sisa hari, negatif = jumlah hari terlambat
        return Carbon::today()->diffInDays($this->tanggal_jatuh_tempo, false);
    }

    const MAX_PERPANJANGAN = 2;
    const LAMA_PERPANJANGAN_HARI = 7;

    /**
     * Bisa diperpanjang kalau: belum dikembalikan, belum lewat jatuh tempo (belum terlambat),
     * dan belum mencapai batas maksimal perpanjangan.
     */
    public function bisaDiperpanjang(): bool
    {
        return is_null($this->tanggal_kembali)
            && $this->status === 'dipinjam'
            && !$this->isTerlambat()
            && $this->jumlah_perpanjangan < self::MAX_PERPANJANGAN;
    }

    public function perpanjang(): void
    {
        $this->update([
            'tanggal_jatuh_tempo' => $this->tanggal_jatuh_tempo->copy()->addDays(self::LAMA_PERPANJANGAN_HARI),
            'jumlah_perpanjangan' => $this->jumlah_perpanjangan + 1,
        ]);
    }
}
