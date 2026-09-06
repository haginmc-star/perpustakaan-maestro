<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['is_maintenance', 'pesan_maintenance'];

    protected $casts = ['is_maintenance' => 'boolean'];

    // Selalu ambil baris pertama (tabel ini didesain cuma punya 1 baris)
    public static function current(): self
    {
        return static::first() ?? static::create(['is_maintenance' => false]);
    }

    public static function isMaintenance(): bool
    {
        return static::current()->is_maintenance;
    }
}
