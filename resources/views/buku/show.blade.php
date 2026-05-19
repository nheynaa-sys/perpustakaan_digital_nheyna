@extends('layouts.app')

@section('title', $buku->judul)
@section('breadcrumb', 'Buku › Detail')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Detail Buku</h4>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="form-card text-center">
            @if($buku->cover)
            <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover"
                 class="img-fluid rounded mb-3" style="max-height:200px;object-fit:cover">
            @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                 style="height:180px; color:#bbb; font-size:3rem">
                <i class="bi bi-book"></i>
            </div>
            @endif
            <h6 class="fw-bold">{{ $buku->judul }}</h6>
            <p class="text-muted small">{{ $buku->pengarang }}</p>

            @if($buku->stok > 0)
            <span class="badge bg-success fs-6">Tersedia: {{ $buku->stok }}</span>
            @else
            <span class="badge bg-danger fs-6">Stok Habis</span>
            @endif

            @if(auth()->user()->role === 'admin')
            <div class="d-flex flex-column gap-2 mt-3">
                <a href="{{ route('buku.edit', $buku->id_buku) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form action="{{ route('buku.destroy', $buku->id_buku) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-card">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Buku</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small">Judul</div>
                    <div class="fw-semibold">{{ $buku->judul }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Pengarang</div>
                    <div>{{ $buku->pengarang }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Penerbit</div>
                    <div>{{ $buku->penerbit ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Tahun Terbit</div>
                    <div>{{ $buku->tahun_terbit ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">ISBN</div>
                    <div>{{ $buku->isbn ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Stok</div>
                    <div>{{ $buku->stok }} buku</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Kategori</div>
                    <div class="mt-1">
                        @forelse($buku->kategori as $k)
                        <span class="badge bg-info text-dark me-1">{{ $k->nama_kategori }}</span>
                        @empty
                        <span class="text-muted">-</span>
                        @endforelse
                    </div>
                </div>
                @if($buku->deskripsi)
                <div class="col-12">
                    <div class="text-muted small">Deskripsi</div>
                    <div class="mt-1">{{ $buku->deskripsi }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Riwayat Peminjaman --}}
        <div class="table-card mt-3">
            <h6 class="fw-bold mb-3">Riwayat Peminjaman</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buku->peminjaman->take(10) as $p)
                        <tr>
                            <td>{{ $p->anggota->nama ?? '-' }}</td>
                            <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $p->tgl_kembali_aktual?->format('d/m/Y') ?? '-' }}</td>
                            <td>{!! $p->status_badge !!}</td>
                            <td>{{ $p->denda > 0 ? $p->denda_format : '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum pernah dipinjam</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
