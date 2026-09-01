@extends('layouts.admin')
@section('title', 'Data Mahasiswa')

@section('content')
<div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
    <h2>Data Mahasiswa</h2>
    <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama / NIM...">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">-- Semua Status --</option>
            <option value="aktif" @selected(request('status')=='aktif')>Aktif</option>
            <option value="dibekukan" @selected(request('status')=='dibekukan')>Dibekukan</option>
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Cari</button></div>
</form>

<div class="table-responsive"><table class="table table-hover bg-white">
    <thead>
        <tr>
            <th>NIM</th><th>Nama</th><th>Jurusan</th><th>Status</th><th>Masa Bekuan</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($mahasiswas as $m)
        <tr>
            <td>{{ $m->nim }}</td>
            <td>{{ $m->nama }}</td>
            <td>{{ $m->jurusan }}</td>
            <td>
                @if($m->status == 'aktif')
                    <span class="badge rounded-pill bg-success">Aktif</span>
                @else
                    <span class="badge rounded-pill bg-danger">Dibekukan</span>
                @endif
            </td>
            <td>
                @if($m->status == 'dibekukan')
                    {{ $m->tanggal_mulai_bekuan?->format('d-m-Y') }} s/d {{ $m->tanggal_selesai_bekuan?->format('d-m-Y') ?? '(tanpa batas)' }}
                    <div class="small text-muted">{{ $m->catatan_bekuan }}</div>
                @else
                    -
                @endif
            </td>
            <td class="d-flex gap-1 flex-wrap">
                <a href="{{ route('admin.mahasiswa.riwayat', $m) }}" class="btn btn-sm btn-outline-secondary">Riwayat</a>
                <a href="{{ route('admin.mahasiswa.edit', $m) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

                @if($m->status == 'aktif')
                    <form action="{{ route('admin.mahasiswa.bekukan', $m) }}" method="POST" onsubmit="return prosesBekukan(event, this)">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Bekukan Manual</button>
                    </form>
                @else
                    <form action="{{ route('admin.mahasiswa.aktifkan', $m) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-outline-success" onclick="return confirm('Aktifkan kembali mahasiswa ini?')">Aktifkan</button>
                    </form>
                @endif

                @if(auth()->user()->role === 'super_admin')
                <form action="{{ route('admin.mahasiswa.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus data mahasiswa ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-dark">Hapus</button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table></div>

{{ $mahasiswas->links() }}

<script>
// Minta alasan sebelum submit form bekukan manual
function prosesBekukan(e, form) {
    e.preventDefault();
    const alasan = prompt('Alasan pembekuan manual (opsional):', '');
    if (alasan === null) return false; // dibatalkan
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'alasan';
    input.value = alasan;
    form.appendChild(input);
    form.submit();
}
</script>
@endsection
