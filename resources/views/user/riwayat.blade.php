@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')
@section('breadcrumb', 'Riwayat Pinjam')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h4 class="mb-1"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Peminjaman Saya</h4>
        <p class="text-muted mb-0 small">Daftar semua peminjaman dan status persetujuan</p>
    </div>
    <a href="{{ route('user.katalog') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-grid me-1"></i>Ke Katalog
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(!$anggota)
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="bi bi-person-exclamation fs-5"></i>
        <div>Akun Anda belum terdaftar sebagai anggota. Hubungi admin.</div>
    </div>
@else

{{-- Ringkasan status --}}
@php
    $allItems   = $peminjaman instanceof \Illuminate\Pagination\LengthAwarePaginator ? $peminjaman->getCollection() : $peminjaman;
    $cntPending = $allItems->where('status', 'pending')->count();
    $cntAktif   = $allItems->where('status', 'dipinjam')->count();
    $cntDone    = $allItems->whereIn('status', ['dikembalikan', 'terlambat'])->count();
    $cntTolak   = $allItems->where('status', 'ditolak')->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-mini pending">
            <div class="stat-val">{{ $cntPending }}</div>
            <div class="stat-lbl">Menunggu</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini aktif">
            <div class="stat-val">{{ $cntAktif }}</div>
            <div class="stat-lbl">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini done">
            <div class="stat-val">{{ $cntDone }}</div>
            <div class="stat-lbl">Dikembalikan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini tolak">
            <div class="stat-val">{{ $cntTolak }}</div>
            <div class="stat-lbl">Ditolak</div>
        </div>
    </div>
</div>

{{-- Tabel riwayat --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Buku</th>
                    <th>Tgl Ajukan</th>
                    <th>Rencana Kembali</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Struk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td class="text-muted small">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($p->buku->cover)
                                <img src="{{ asset('storage/' . $p->buku->cover) }}"
                                     alt="cover" class="rounded" style="width:32px;height:44px;object-fit:cover">
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                     style="width:32px;height:44px;color:#aaa">
                                    <i class="bi bi-book" style="font-size:.8rem"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $p->buku->judul }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $p->buku->pengarang }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="small">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td class="small">{{ \Carbon\Carbon::parse($p->tgl_kembali_rencana)->format('d/m/Y') }}</td>
                    <td class="small">
                        {{ $p->tgl_kembali_aktual ? \Carbon\Carbon::parse($p->tgl_kembali_aktual)->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        @switch($p->status)
                            @case('pending')
                                <span class="badge status-badge pending-badge">
                                    <i class="bi bi-hourglass-split me-1"></i>Menunggu
                                </span>
                                @break
                            @case('dipinjam')
                                <span class="badge status-badge dipinjam-badge">
                                    <i class="bi bi-book me-1"></i>Dipinjam
                                </span>
                                @break
                            @case('dikembalikan')
                                <span class="badge status-badge kembali-badge">
                                    <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                </span>
                                @break
                            @case('terlambat')
                                <span class="badge status-badge terlambat-badge">
                                    <i class="bi bi-exclamation-circle me-1"></i>Terlambat
                                </span>
                                @break
                            @case('ditolak')
                                <span class="badge status-badge tolak-badge" title="{{ $p->catatan_admin }}">
                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                </span>
                                @if($p->catatan_admin)
                                    <div class="text-muted" style="font-size:.7rem">{{ $p->catatan_admin }}</div>
                                @endif
                                @break
                        @endswitch
                    </td>
                    <td>
                        @if($p->denda > 0)
                            <span class="text-danger fw-semibold small">
                                Rp {{ number_format($p->denda, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>
                        @if($p->bolehDilihatAnggota())
                            <a href="{{ route('user.struk', $p->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-printer me-1"></i>Lihat
                            </a>
                        @elseif(in_array($p->status, ['dipinjam', 'dikembalikan', 'terlambat']))
                            <span class="badge bg-light text-muted border">Menunggu dikirim</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                        Belum ada riwayat peminjaman.
                        <a href="{{ route('user.katalog') }}" class="d-block mt-2 small">Mulai pinjam buku →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peminjaman->hasPages())
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan {{ $peminjaman->firstItem() }}–{{ $peminjaman->lastItem() }}
            dari {{ $peminjaman->total() }} data
        </div>
        {{ $peminjaman->links() }}
    </div>
    @endif
</div>

@endif
@endsection

@push('styles')
<style>
.stat-mini {
    border-radius: 12px;
    padding: 1rem 1.2rem;
    text-align: center;
}
.stat-mini .stat-val { font-size: 1.6rem; font-weight: 700; line-height: 1; }
.stat-mini .stat-lbl { font-size: .75rem; margin-top: .3rem; opacity: .8; }
.stat-mini.pending  { background: #FEF9C3; color: #854D0E; }
.stat-mini.aktif    { background: #DBEAFE; color: #1E40AF; }
.stat-mini.done     { background: #DCFCE7; color: #166534; }
.stat-mini.tolak    { background: #FEE2E2; color: #991B1B; }

.status-badge { font-size: .75rem; padding: .3em .7em; border-radius: 6px; }
.pending-badge   { background: #FEF9C3; color: #854D0E; }
.dipinjam-badge  { background: #DBEAFE; color: #1E40AF; }
.kembali-badge   { background: #DCFCE7; color: #166534; }
.terlambat-badge { background: #FEE2E2; color: #991B1B; }
.tolak-badge     { background: #F3F4F6; color: #6B7280; }
</style>
@endpush
