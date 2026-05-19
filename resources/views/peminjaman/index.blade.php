@extends('layouts.app')

@section('title', 'Peminjaman')
@section('breadcrumb', 'Peminjaman')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4><i class="bi bi-arrow-left-right me-2 text-primary"></i>Daftar Peminjaman</h4>
        <p class="text-muted mb-0 small">Kelola peminjaman & pengembalian buku</p>
    </div>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Peminjaman
    </a>
</div>

{{-- Filter --}}
<div class="table-card mb-3">
    <form method="GET" action="{{ route('peminjaman.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Filter Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="dipinjam"    {{ request('status') == 'dipinjam'    ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan"{{ request('status') == 'dikembalikan'? 'selected' : '' }}>Dikembalikan</option>
                <option value="terlambat"   {{ request('status') == 'terlambat'   ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tgl Dari</label>
            <input type="date" name="tgl_dari" class="form-control" value="{{ request('tgl_dari') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tgl Sampai</label>
            <input type="date" name="tgl_sampai" class="form-control" value="{{ request('tgl_sampai') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Rencana Kembali</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td class="text-muted small">{{ $peminjaman->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="fw-semibold">{{ $p->anggota->nama }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $p->anggota->kelas }}</div>
                    </td>
                    <td>{{ Str::limit($p->buku->judul, 30) }}</td>
                    <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                    <td>
                        {{ $p->tgl_kembali_rencana->format('d/m/Y') }}
                        @if($p->status === 'dipinjam' && $p->tgl_kembali_rencana->isPast())
                        <span class="badge bg-danger ms-1">Lewat!</span>
                        @endif
                    </td>
                    <td>{!! $p->status_badge !!}</td>
                    <td>
                        @if($p->denda > 0)
                        <span class="text-danger fw-semibold">{{ $p->denda_format }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('peminjaman.show', $p->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(in_array($p->status, ['dipinjam', 'dikembalikan', 'terlambat']))
                            <a href="{{ route('peminjaman.struk', $p->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak struk">
                                <i class="bi bi-printer"></i>
                            </a>
                            @if(!$p->struk_disetujui_at)
                            <form action="{{ route('peminjaman.approveStruk', $p->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-success" title="Kirim struk ke anggota">
                                    <i class="bi bi-send"></i>
                                </button>
                            </form>
                            @else
                            <span class="btn btn-sm btn-success disabled" title="Struk sudah dikirim">
                                <i class="bi bi-send-check"></i>
                            </span>
                            @endif
                            @endif
                            @if($p->status === 'dipinjam' && auth()->user()->role === 'admin')
                            <form action="{{ route('peminjaman.kembalikan', $p->id) }}" method="POST"
                                  onsubmit="return confirm('Proses pengembalian buku ini?')">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Kembalikan">
                                    <i class="bi bi-check-circle"></i> Kembalikan
                                </button>
                            </form>
                            @endif
                            @if(auth()->user()->role === 'admin')
                            <form action="{{ route('peminjaman.destroy', $p->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus data peminjaman ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Tidak ada data peminjaman
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjaman->hasPages())
    <div class="mt-3">{{ $peminjaman->links() }}</div>
    @endif
</div>
@endsection
