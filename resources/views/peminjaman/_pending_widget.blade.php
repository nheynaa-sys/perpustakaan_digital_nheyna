{{-- resources/views/peminjaman/_pending_widget.blade.php --}}

@if(auth()->user()->role === 'admin' && $pendingCount > 0)
<div class="alert alert-warning d-flex align-items-center gap-3 mb-3 shadow-sm" role="alert">
    <i class="bi bi-bell-fill fs-4 text-warning"></i>
    <div class="flex-grow-1">
        <div class="fw-semibold">
            Ada <strong>{{ $pendingCount }}</strong> permintaan peminjaman menunggu persetujuan.
        </div>
        <div class="small text-muted">Segera tinjau agar anggota tidak menunggu terlalu lama.</div>
    </div>
    <a href="{{ route('peminjaman.pending') }}" class="btn btn-warning btn-sm fw-semibold text-nowrap">
        <i class="bi bi-eye me-1"></i>Tinjau Sekarang
    </a>
</div>
@endif

@if(auth()->user()->role === 'admin' && $pendingPeminjaman->isNotEmpty())
<div class="table-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold">
            <i class="bi bi-clock-history me-2 text-warning"></i>
            Permintaan Menunggu Persetujuan
            <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
        </h6>
        @if($pendingCount > 5)
        <a href="{{ route('peminjaman.pending') }}" class="btn btn-sm btn-outline-warning">
            Lihat Semua ({{ $pendingCount }})
        </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead class="table-light">
                <tr>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tgl Pengajuan</th>
                    <th>Stok</th>
                    <th style="width:180px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingPeminjaman as $p)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $p->anggota->nama ?? '-' }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $p->anggota->kelas }}</div>
                    </td>
                    <td>
                        <div>{{ Str::limit($p->buku->judul, 28) }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $p->buku->pengarang }}</div>
                    </td>
                    <td class="text-muted small">{{ $p->created_at->diffForHumans() }}</td>
                    <td>
                        @if($p->buku->stok > 5)
                            <span class="badge bg-success">{{ $p->buku->stok }}</span>
                        @elseif($p->buku->stok > 0)
                            <span class="badge bg-warning text-dark">{{ $p->buku->stok }}</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @if($p->buku->stok > 0)
                            <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Setujui permintaan ini?')"
                                        title="Setujui">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="bi bi-check-lg"></i>
                            </button>
                            @endif

                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTolakDashboard"
                                    data-id="{{ $p->id }}"
                                    data-nama="{{ $p->anggota->nama ?? '' }}"
                                    data-buku="{{ $p->buku->judul }}"
                                    title="Tolak">
                                <i class="bi bi-x-lg"></i>
                            </button>

                            <a href="{{ route('peminjaman.show', $p->id) }}"
                               class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolakDashboard" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-x-circle text-danger me-2"></i>Tolak Permintaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTolakDashboard" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Tolak peminjaman <strong id="td-buku"></strong>
                        dari <strong id="td-nama"></strong>?
                    </p>
                    <div>
                        <label class="form-label small fw-semibold">Alasan Penolakan (opsional)</label>
                        <textarea name="catatan_admin" class="form-control" rows="3"
                                  placeholder="Contoh: Stok sedang habis..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('modalTolakDashboard')?.addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('td-buku').textContent = btn.dataset.buku;
    document.getElementById('td-nama').textContent = btn.dataset.nama;
    document.getElementById('formTolakDashboard').action = '/peminjaman/' + btn.dataset.id + '/reject';
});
</script>
@endpush