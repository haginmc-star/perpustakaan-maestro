<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_maintenance')->default(false);
            $table->text('pesan_maintenance')->nullable();
            $table->timestamps();
        });

        // Buat 1 baris default (singleton), supaya bisa langsung dipakai
        DB::table('site_settings')->insert([
            'is_maintenance' => false,
            'pesan_maintenance' => 'Web sedang dalam pemeliharaan. Silakan kembali lagi nanti.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
