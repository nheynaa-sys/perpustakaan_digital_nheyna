@extends('layouts.app')

@section('title', 'Kategori Buku')
@section('breadcrumb', 'Kategori')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4><i class="bi bi-tags me-2 text-primary"></i>Kategori Buku</h4>
    </div>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Buku</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $k)
                <tr>
                    <td>{{ $kategoris->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $k->nama_kategori }}</td>
                    <td class="text-muted">{{ Str::limit($k->deskripsi, 60) ?? '-' }}</td>
                    <td><span class="badge bg-info text-dark">{{ $k->buku_count }} buku</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('kategori.edit', $k->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('kategori.destroy', $k->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus kategori {{ addslashes($k->nama_kategori) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kategoris->hasPages())
    <div class="mt-3">{{ $kategoris->links() }}</div>
    @endif
</div>
@endsection