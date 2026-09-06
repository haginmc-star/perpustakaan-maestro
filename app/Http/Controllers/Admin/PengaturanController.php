<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::current();
        return view('admin.pengaturan.index', compact('setting'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'pesan_maintenance' => 'nullable|string|max:500',
        ]);

        $setting = SiteSetting::current();
        $statusBaru = !$setting->is_maintenance;

        $setting->update([
            'is_maintenance' => $statusBaru,
            'pesan_maintenance' => $request->input('pesan_maintenance', $setting->pesan_maintenance),
        ]);

        ActivityLog::catat(
            $statusBaru ? 'Mengaktifkan Maintenance Mode' : 'Menonaktifkan Maintenance Mode',
            $statusBaru ? 'Web ditutup sementara untuk pengunjung.' : 'Web dibuka kembali untuk pengunjung.'
        );

        $pesan = $statusBaru
            ? 'Web sekarang TERTUTUP untuk pengunjung. Hanya admin/staff yang bisa akses.'
            : 'Web sekarang TERBUKA kembali untuk pengunjung.';

        return back()->with('success', $pesan);
    }
}
