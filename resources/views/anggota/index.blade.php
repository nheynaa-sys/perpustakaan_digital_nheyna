@extends('layouts.app')

@section('title', 'Data Anggota')
@section('breadcrumb', 'Anggota')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4><i class="bi bi-people me-2 text-primary"></i>Data Anggota</h4>
        <p class="text-muted mb-0 small">Kelola data anggota perpustakaan</p>
    </div>
    <a href="{{ route('anggota.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Anggota
    </a>
</div>

{{-- Pencarian --}}
<div class="table-card mb-3">
    <form method="GET" action="{{ route('anggota.index') }}" class="row g-2 align-items-end">
        <div class="col-md-6">
            <label class="form-label">Cari Anggota</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control"
                       placeholder="Nama, NIS, atau kelas..."
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button>
            <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>No. HP</th>
                    <th>Pinjaman Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggota as $a)
                <tr>
                    <td class="text-muted small">{{ $anggota->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $a->nis }}</td>
                    <td>
                        <a href="{{ route('anggota.show', $a->id) }}" class="text-decoration-none text-dark fw-semibold">
                            {{ $a->nama }}
                        </a>
                    </td>
                    <td>{{ $a->kelas }}</td>
                    <td class="text-muted">{{ $a->no_hp ?? '-' }}</td>
                    <td>
                        @if($a->pinjaman_aktif > 0)
                        <span class="badge bg-warning text-dark">{{ $a->pinjaman_aktif }} buku</span>
                        @else
                        <span class="badge bg-success">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('anggota.show', $a->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('anggota.edit', $a->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('anggota.destroy', $a->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus anggota {{ addslashes($a->nama) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                        Belum ada data anggota
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($anggota->hasPages())
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan {{ $anggota->firstItem() }}–{{ $anggota->lastItem() }} dari {{ $anggota->total() }} anggota
        </div>
        {{ $anggota->links() }}
    </div>
    @endif
</div>
@endsection