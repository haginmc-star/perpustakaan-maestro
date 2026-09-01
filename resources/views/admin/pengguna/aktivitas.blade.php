@extends('layouts.admin')
@section('title', 'Log Aktivitas')

@section('content')
<h2 class="mb-3">Log Aktivitas</h2>
<p class="text-muted">Riwayat semua aksi penting yang dilakukan oleh admin/staff di sistem ini.</p>

<table class="table table-hover bg-white">
    <thead>
        <tr><th>Waktu</th><th>Dilakukan Oleh</th><th>Aksi</th><th>Keterangan</th></tr>
    </thead>
    <tbody>
        @forelse($logs as $log)
        <tr>
            <td class="font-mono small">{{ $log->created_at->format('d-m-Y H:i') }}</td>
            <td>{{ $log->user->name ?? '(akun dihapus)' }}</td>
            <td><span class="badge rounded-pill bg-primary">{{ $log->aksi }}</span></td>
            <td class="small">{{ $log->keterangan }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center text-muted">Belum ada aktivitas tercatat.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $logs->links() }}
@endsection
