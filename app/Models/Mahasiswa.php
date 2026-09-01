<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim', 'nama', 'jurusan', 'no_hp',
        'status', 'tanggal_mulai_bekuan', 'tanggal_selesai_bekuan', 'catatan_bekuan',
    ];

    protected $casts = [
        'tanggal_mulai_bekuan' => 'date',
        'tanggal_selesai_bekuan' => 'date',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Bekukan mahasiswa otomatis selama N hari (default 3) karena terlambat mengembalikan buku.
     */
    public function bekukanOtomatis(int $hari = 3): void
    {
        $this->update([
            'status' => 'dibekukan',
            'tanggal_mulai_bekuan' => Carbon::today(),
            'tanggal_selesai_bekuan' => Carbon::today()->addDays($hari),
            'catatan_bekuan' => "Dibekukan otomatis karena terlambat mengembalikan buku ({$hari} hari).",
        ]);
    }

    /**
     * Admin membekukan/mengaktifkan manual, dengan catatan alasan.
     */
    public function bekukanManual(?string $alasan, ?int $hari = null): void
    {
        $this->update([
            'status' => 'dibekukan',
            'tanggal_mulai_bekuan' => Carbon::today(),
            'tanggal_selesai_bekuan' => $hari ? Carbon::today()->addDays($hari) : null,
            'catatan_bekuan' => $alasan ?: 'Dibekukan manual oleh admin.',
        ]);
    }

    public function aktifkanManual(?string $alasan = null): void
    {
        $this->update([
            'status' => 'aktif',
            'tanggal_mulai_bekuan' => null,
            'tanggal_selesai_bekuan' => null,
            'catatan_bekuan' => $alasan ?: 'Diaktifkan kembali manual oleh admin.',
        ]);
    }

    /**
     * Cek apakah masa bekuan sudah lewat, kalau iya otomatis aktifkan lagi.
     * Dipanggil setiap kali data mahasiswa diakses/ditampilkan.
     */
    public function cekDanBukaBekuanOtomatis(): void
    {
        if ($this->status === 'dibekukan'
            && $this->tanggal_selesai_bekuan
            && Carbon::today()->greaterThanOrEqualTo($this->tanggal_selesai_bekuan)
        ) {
            $this->update([
                'status' => 'aktif',
                'catatan_bekuan' => 'Diaktifkan otomatis (masa bekuan 3 hari selesai).',
            ]);
        }
    }

    public function bisaMeminjam(): bool
    {
        $this->cekDanBukaBekuanOtomatis();
        return $this->status === 'aktif';
    }
}
