{{-- resources/views/peminjaman/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('breadcrumb', 'Peminjaman › Detail')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Detail Peminjaman #{{ $peminjaman->id }}</h4>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="form-card">

    {{-- ── Status Banner ── --}}
    @php
        $statusConfig = match($peminjaman->status) {
            'dipinjam'     => ['class' => 'info',    'icon' => 'bi-book',              'label' => 'Sedang Dipinjam'],
            'dikembalikan' => ['class' => 'success',  'icon' => 'bi-check-circle',      'label' => 'Sudah Dikembalikan'],
            'terlambat'    => ['class' => 'danger',   'icon' => 'bi-exclamation-circle', 'label' => 'Terlambat Dikembalikan'],
            'pending'      => ['class' => 'warning',  'icon' => 'bi-clock',             'label' => 'Menunggu Persetujuan'],
            'ditolak'      => ['class' => 'secondary','icon' => 'bi-x-circle',          'label' => 'Permintaan Ditolak'],
            default        => ['class' => 'secondary','icon' => 'bi-question-circle',   'label' => ucfirst($peminjaman->status)],
        };
    @endphp
    <div class="alert alert-{{ $statusConfig['class'] }} d-flex align-items-center gap-2 mb-4">
        <i class="bi {{ $statusConfig['icon'] }} fs-5"></i>
        <span class="fw-semibold">{{ $statusConfig['label'] }}</span>
        @if($peminjaman->status === 'dipinjam' && $peminjaman->tgl_kembali_rencana->isPast())
            <span class="ms-auto badge bg-danger">
                Terlambat {{ $peminjaman->tgl_kembali_rencana->diffForHumans() }}
            </span>
        @endif
    </div>

    <div class="row g-3">
        {{-- Anggota --}}
        <div class="col-md-6">
            <div class="text-muted small mb-1">
                <i class="bi bi-person me-1"></i>Anggota
            </div>
            <div class="fw-bold">{{ $peminjaman->anggota->nama }}</div>
            <div class="text-muted small">
                {{ $peminjaman->anggota->kelas }} — NIS {{ $peminjaman->anggota->nis }}
            </div>
        </div>

        {{-- Buku --}}
        <div class="col-md-6">
            <div class="text-muted small mb-1">
                <i class="bi bi-book me-1"></i>Buku
            </div>
            <div class="fw-bold">{{ $peminjaman->buku->judul }}</div>
            <div class="text-muted small">{{ $peminjaman->buku->pengarang }}</div>
        </div>

        {{-- Tanggal --}}
        <div class="col-md-4">
            <div class="text-muted small mb-1"><i class="bi bi-calendar me-1"></i>Tgl Pinjam</div>
            <div>{{ $peminjaman->tgl_pinjam->format('d F Y') }}</div>
        </div>
        <div class="col-md-4">
            <div class="text-muted small mb-1"><i class="bi bi-calendar-check me-1"></i>Rencana Kembali</div>
            <div>{{ $peminjaman->tgl_kembali_rencana->format('d F Y') }}</div>
        </div>
        <div class="col-md-4">
            <div class="text-muted small mb-1"><i class="bi bi-calendar2-check me-1"></i>Aktual Kembali</div>
            <div>{{ $peminjaman->tgl_kembali_aktual?->format('d F Y') ?? '-' }}</div>
        </div>

        {{-- Status + Denda --}}
        <div class="col-md-6">
            <div class="text-muted small mb-1">Status</div>
            <div>{!! $peminjaman->status_badge !!}</div>
        </div>
        <div class="col-md-6">
            <div class="text-muted small mb-1"><i class="bi bi-cash me-1"></i>Denda</div>
            @if($peminjaman->denda > 0)
            <div class="text-danger fw-bold">
                {{ $peminjaman->denda_format }}
                <span class="text-muted fw-normal small">(Rp 1.000/hari)</span>
            </div>
            @else
            <div class="text-success"><i class="bi bi-check-circle me-1"></i>Tidak ada denda</div>
            @endif
        </div>

        {{-- Catatan admin --}}
        @if(!empty($peminjaman->catatan_admin))
        <div class="col-12">
            <div class="text-muted small mb-1"><i class="bi bi-chat-left-text me-1"></i>Catatan Petugas</div>
            <div class="alert alert-warning py-2 mb-0 small">{{ $peminjaman->catatan_admin }}</div>
        </div>
        @endif
    </div>

    <hr>

    {{-- ── ACTION BUTTONS ── --}}
    <div class="d-flex flex-wrap gap-2">

        {{-- Kembalikan (admin only) --}}
        @if($peminjaman->status === 'dipinjam' && auth()->user()->role === 'admin')
        <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST"
              onsubmit="return confirm('Proses pengembalian buku ini?')">
            @csrf
            <button class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i>Proses Pengembalian
            </button>
        </form>
        @endif

        {{-- Approve/Reject dari show (admin, status pending) --}}
        @if($peminjaman->status === 'pending' && auth()->user()->role === 'admin')
        <form action="{{ route('peminjaman.approve', $peminjaman->id) }}" method="POST"
              onsubmit="return confirm('Setujui permintaan peminjaman ini?')">
            @csrf @method('PATCH')
            <button class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>Setujui
            </button>
        </form>
        <button class="btn btn-outline-danger"
                data-bs-toggle="modal" data-bs-target="#modalTolakShow"
                data-id="{{ $peminjaman->id }}"
                data-nama="{{ $peminjaman->anggota->nama }}"
                data-buku="{{ $peminjaman->buku->judul }}">
            <i class="bi bi-x-lg me-1"></i>Tolak
        </button>
        @endif

        {{-- Cetak Struk --}}
        @if(in_array($peminjaman->status, ['dipinjam', 'dikembalikan', 'terlambat']))
        <a href="{{ route('peminjaman.struk', $peminjaman->id) }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-printer me-1"></i>Cetak Struk
        </a>

        @if(auth()->user()->role === 'admin')
            @if(!$peminjaman->struk_disetujui_at)
            <form action="{{ route('peminjaman.approveStruk', $peminjaman->id) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-outline-success">
                    <i class="bi bi-send me-1"></i>Kirim Struk ke Anggota
                </button>
            </form>
            @else
            <span class="btn btn-success disabled">
                <i class="bi bi-send-check me-1"></i>Struk Sudah Dikirim
            </span>
            @endif
        @endif
        @endif

        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary ms-auto">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

</div>
</div>
</div>

{{-- Modal Tolak (untuk tampilan show) --}}
<div class="modal fade" id="modalTolakShow" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-x-circle text-danger me-2"></i>Tolak Permintaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTolakShow" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Tolak peminjaman <strong id="ts-buku"></strong>
                        dari <strong id="ts-nama"></strong>?
                    </p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alasan Penolakan (opsional)</label>
                        <textarea name="catatan_admin" class="form-control" rows="3"
                                  placeholder="Contoh: Buku sedang tidak tersedia..."></textarea>
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
@endsection

@push('scripts')
<script>
document.getElementById('modalTolakShow')?.addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('ts-buku').textContent = btn.dataset.buku;
    document.getElementById('ts-nama').textContent = btn.dataset.nama;
    document.getElementById('formTolakShow').action = '/peminjaman/' + btn.dataset.id + '/reject';
});
</script>
@endpush
