@extends('layouts.app')

@section('title', 'Daftar Buku')
@section('breadcrumb', 'Buku')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4><i class="bi bi-book me-2 text-primary"></i>Daftar Buku</h4>
        <p class="text-muted mb-0 small">Kelola koleksi buku perpustakaan</p>
    </div>
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('buku.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Buku
    </a>
    @endif
</div>

{{-- Filter & Pencarian --}}
<div class="table-card mb-3">
    <form method="GET" action="{{ route('buku.index') }}" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label">Cari Buku</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control"
                       placeholder="Judul, pengarang, atau ISBN..."
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Filter Kategori</label>
            <select name="kategori_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search me-1"></i>Cari</button>
            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

{{-- Tabel Buku --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Buku</th>
                    <th>Pengarang</th>
                    <th>ISBN</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    @if(auth()->user()->role === 'admin')
                    <th style="width:140px">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($buku as $b)
                <tr>
                    <td class="text-muted small">{{ $buku->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($b->cover)
                            <img src="{{ asset('storage/' . $b->cover) }}"
                                 alt="cover" class="rounded" style="width:38px;height:50px;object-fit:cover">
                            @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                 style="width:38px;height:50px; color:#aaa">
                                <i class="bi bi-book"></i>
                            </div>
                            @endif
                            <div>
                                <a href="{{ route('buku.show', $b->id_buku) }}" class="fw-semibold text-decoration-none text-dark">
                                    {{ $b->judul }}
                                </a>
                                @if($b->tahun_terbit)
                                <div class="text-muted" style="font-size:.75rem">{{ $b->tahun_terbit }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $b->pengarang }}</td>
                    <td class="text-muted small">{{ $b->isbn ?? '-' }}</td>
                    <td>
                        @foreach($b->kategori as $k)
                        <span class="badge bg-info text-dark me-1">{{ $k->nama_kategori }}</span>
                        @endforeach
                    </td>
                    <td>
                        @if($b->stok > 5)
                            <span class="badge bg-success">{{ $b->stok }}</span>
                        @elseif($b->stok > 0)
                            <span class="badge bg-warning text-dark">{{ $b->stok }}</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </td>
                    @if(auth()->user()->role === 'admin')
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('buku.edit', $b->id_buku) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('buku.destroy', $b->id_buku) }}" method="POST"
                                  onsubmit="return confirm('Hapus buku {{ addslashes($b->judul) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Tidak ada data buku
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($buku->hasPages())
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan {{ $buku->firstItem() }}–{{ $buku->lastItem() }} dari {{ $buku->total() }} buku
        </div>
        {{ $buku->links() }}
    </div>
    @endif
</div>
@endsection