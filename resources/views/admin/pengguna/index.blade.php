@extends('layouts.admin')
@section('title', 'Kelola Akun Admin')

@section('content')
<div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
    <h2>Kelola Akun Admin &amp; Staff</h2>
    <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary">+ Tambah Akun</a>
</div>

<div class="table-responsive"><table class="table table-hover bg-white">
    <thead>
        <tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach($users as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>
                @if($u->role == 'super_admin')
                    <span class="badge rounded-pill bg-warning text-dark">Super Admin</span>
                @else
                    <span class="badge rounded-pill bg-secondary">Staff</span>
                @endif
            </td>
            <td>
                @if($u->id !== auth()->id())
                    <form action="{{ route('admin.pengguna.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-dark">Hapus</button>
                    </form>
                @else
                    <span class="text-muted small">(Akun kamu)</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table></div>
@endsection
