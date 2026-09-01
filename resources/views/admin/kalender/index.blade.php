@extends('layouts.admin')
@section('title', 'Kalender Jatuh Tempo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="mb-0">Kalender Jatuh Tempo</h2>

    <form method="GET" class="d-flex gap-2 flex-wrap">
        <select name="bulan" class="form-select">
            @foreach(range(1,12) as $b)
                <option value="{{ $b }}" @selected($bulan == $b)>{{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
            @endforeach
        </select>
        <input type="number" name="tahun" value="{{ $tahun }}" class="form-control" style="width:100px">
        <button class="btn btn-outline-primary">Tampilkan</button>
    </form>
</div>

<div class="d-flex gap-3 mb-3 small">
    <span><span class="badge rounded-pill bg-primary">&nbsp;</span> Jatuh tempo (belum lewat)</span>
    <span><span class="badge rounded-pill bg-danger">&nbsp;</span> Terlambat</span>
</div>

<div class="card">
    <div class="card-body p-2">
        <div class="table-responsive">
        <table class="table table-bordered mb-0" style="table-layout: fixed; min-width: 700px;">
            <thead>
                <tr class="text-center">
                    <th>Minggu</th><th>Senin</th><th>Selasa</th><th>Rabu</th><th>Kamis</th><th>Jumat</th><th>Sabtu</th>
                </tr>
            </thead>
            <tbody>
                @php $mingguList = collect($hariGrid)->chunk(7); @endphp

                @foreach($mingguList as $minggu)
                <tr style="height: 110px;">
                    @foreach($minggu as $hari)
                        @php
                            $key = $hari->format('Y-m-d');
                            $itemHariIni = $perTanggal->get($key, collect());
                            $bukanBulanIni = $hari->month != $bulan;
                        @endphp
                        <td class="align-top p-1 {{ $bukanBulanIni ? 'bg-light text-muted' : '' }}" style="overflow-y:auto;">
                            <div class="fw-bold small">{{ $hari->day }}</div>
                            @foreach($itemHariIni as $p)
                                @php
                                    $telat = \Carbon\Carbon::today()->greaterThan($p->tanggal_jatuh_tempo);
                                @endphp
                                <div class="small mb-1 p-1 rounded {{ $telat ? 'bg-danger-subtle border border-danger' : 'bg-primary-subtle border border-primary' }}"
                                     title="{{ $p->buku->judul }}">
                                    <strong>{{ Str::limit($p->mahasiswa->nama, 14) }}</strong><br>
                                    {{ Str::limit($p->buku->judul, 18) }}
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>

<p class="text-muted small mt-2">Klik menu "Peminjaman" di sidebar untuk memproses pengembalian/perpanjangan.</p>
@endsection
