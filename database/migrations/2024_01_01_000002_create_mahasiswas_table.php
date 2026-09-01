<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('jurusan')->nullable();
            $table->string('no_hp')->nullable();
            // status: aktif atau dibekukan
            $table->enum('status', ['aktif', 'dibekukan'])->default('aktif');
            $table->date('tanggal_mulai_bekuan')->nullable();
            $table->date('tanggal_selesai_bekuan')->nullable();
            $table->string('catatan_bekuan')->nullable(); // alasan kalau dibekukan/dibuka manual oleh admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
