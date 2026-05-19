@extends('layouts.app')

@section('title', 'Permintaan Peminjaman')
@section('breadcrumb', 'Peminjaman › Permintaan Masuk')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h4 class="mb-1">
            <i class="bi bi-bell me-2 text-warning"></i>Permintaan Peminjaman
            @if($peminjaman->total() > 0)
                <span class="badge bg-warning text-dark ms-1">{{ $peminjaman->total() }}</span>
            @endif
        </h4>
        <p class="text-muted mb-0 small">Tinjau dan proses permintaan peminjaman dari anggota</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-list-ul me-1"></i>Semua Peminjaman
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tgl Pengajuan</th>
                    <th>Rencana Kembali</th>
                    <th>Stok</th>
                    <th style="width:200px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td class="text-muted small">{{ $peminjaman->firstItem() + $loop->index }}</td>

                    {{-- Anggota --}}
                    <td>
                        <div class="fw-semibold" style="font-size:.875rem">{{ $p->anggota->nama ?? '-' }}</div>
                        <div class="text-muted" style="font-size:.75rem">
                            {{ $p->anggota->nis ?? '' }}
                            @if($p->anggota->kelas) · {{ $p->anggota->kelas }} @endif
                        </div>
                    </td>

                    {{-- Buku --}}
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

                    <td class="small">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td class="small">{{ \Carbon\Carbon::parse($p->tgl_kembali_rencana)->format('d/m/Y') }}</td>

                    {{-- Stok saat ini --}}
                    <td>
                        @if($p->buku->stok > 5)
                            <span class="badge bg-success">{{ $p->buku->stok }}</span>
                        @elseif($p->buku->stok > 0)
                            <span class="badge bg-warning text-dark">{{ $p->buku->stok }}</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            {{-- Tombol Setujui --}}
                            @if($p->buku->stok > 0)
                            <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Setujui permintaan ini?')"
                                        title="Setujui">
                                    <i class="bi bi-check-lg me-1"></i>Setujui
                                </button>
                            </form>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled title="Stok habis">
                                <i class="bi bi-check-lg me-1"></i>Setujui
                            </button>
                            @endif

                            {{-- Tombol Tolak --}}
                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTolak"
                                    data-id="{{ $p->id }}"
                                    data-nama="{{ $p->anggota->nama ?? '' }}"
                                    data-buku="{{ $p->buku->judul }}"
                                    title="Tolak">
                                <i class="bi bi-x-lg me-1"></i>Tolak
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                        Tidak ada permintaan peminjaman yang menunggu.
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
            dari {{ $peminjaman->total() }} permintaan
        </div>
        {{ $peminjaman->links() }}
    </div>
    @endif
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-x-circle text-danger me-2"></i>Tolak Permintaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTolak" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Anda akan menolak permintaan pinjam <strong id="tolak-buku"></strong>
                        dari anggota <strong id="tolak-nama"></strong>.
                    </p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alasan Penolakan (opsional)</label>
                        <textarea name="catatan_admin" class="form-control" rows="3"
                                  placeholder="Contoh: Stok sedang habis, silakan coba lagi nanti..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalTolak');
    modal.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('tolak-buku').textContent = btn.dataset.buku;
        document.getElementById('tolak-nama').textContent = btn.dataset.nama;
        document.getElementById('formTolak').action =
            '/peminjaman/' + btn.dataset.id + '/reject';
    });
});
</script>
@endpush