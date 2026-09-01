<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'aksi', 'keterangan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper cepat untuk mencatat aktivitas dari controller manapun
    public static function catat(string $aksi, ?string $keterangan = null): void
    {
        static::create([
            'user_id' => Auth::id(),
            'aksi' => $aksi,
            'keterangan' => $keterangan,
        ]);
    }
}
