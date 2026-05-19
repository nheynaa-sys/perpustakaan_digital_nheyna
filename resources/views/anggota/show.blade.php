@extends('layouts.app')

@section('title', $anggota->nama)
@section('breadcrumb', 'Anggota › Detail')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0">Detail Anggota</h4>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="form-card text-center">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold fs-2"
                 style="width:80px;height:80px">
                {{ strtoupper(substr($anggota->nama, 0, 1)) }}
            </div>
            <h5 class="fw-bold">{{ $anggota->nama }}</h5>
            <p class="text-muted mb-0">{{ $anggota->kelas }}</p>
            <span class="badge bg-secondary">NIS: {{ $anggota->nis }}</span>

            <hr>
            <div class="text-start">
                <div class="mb-2">
                    <i class="bi bi-phone me-2 text-muted"></i>
                    <span>{{ $anggota->no_hp ?? 'Tidak ada' }}</span>
                </div>
                <div>
                    <i class="bi bi-geo-alt me-2 text-muted"></i>
                    <span>{{ $anggota->alamat ?? 'Tidak ada' }}</span>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 mt-3">
                <a href="{{ route('anggota.index', $anggota->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman</h6>
                <span class="badge bg-secondary">{{ $peminjaman->total() }} total</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $p)
                        <tr>
                            <td>
                                <a href="{{ route('buku.show', $p->buku->id_buku) }}"
                                   class="text-decoration-none">
                                    {{ Str::limit($p->buku->judul, 30) }}
                                </a>
                            </td>
                            <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $p->tgl_kembali_aktual?->format('d/m/Y') ?? $p->tgl_kembali_rencana->format('d/m/Y') . ' (rencana)' }}</td>
                            <td>{!! $p->status_badge !!}</td>
                            <td>{{ $p->denda > 0 ? $p->denda_format : '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum pernah meminjam</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $peminjaman->links() }}
        </div>
    </div>
</div>
@endsection