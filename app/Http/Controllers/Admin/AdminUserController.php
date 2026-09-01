<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:super_admin,staff',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        ActivityLog::catat('Menambah Akun Admin', "Menambahkan akun {$data['name']} ({$data['role']})");

        return redirect()->route('admin.pengguna.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function destroy(User $pengguna)
    {
        if ($pengguna->id === Auth::id()) {
            return back()->withErrors(['pengguna' => 'Kamu tidak bisa menghapus akunmu sendiri.']);
        }

        $nama = $pengguna->name;
        $pengguna->delete();

        ActivityLog::catat('Menghapus Akun Admin', "Menghapus akun {$nama}");

        return back()->with('success', 'Akun berhasil dihapus.');
    }

    // Halaman log aktivitas, juga khusus super admin
    public function aktivitas()
    {
        $logs = ActivityLog::with('user')->orderByDesc('created_at')->paginate(30);
        return view('admin.pengguna.aktivitas', compact('logs'));
    }
}
