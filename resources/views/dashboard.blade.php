@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h4 class="mb-1"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</h4>
        <p class="text-muted mb-0 small">Selamat datang, <strong>{{ auth()->user()->name }}</strong> 👋</p>
    </div>
    <div class="text-muted small">{{ now()->translatedFormat('l, d F Y') }}</div>
</div>

{{-- ── Alert Pending (admin only) ── --}}
@if(auth()->user()->role === 'admin' && $pendingCount > 0)
<div class="alert alert-warning d-flex align-items-center gap-3 mb-4 shadow-sm" role="alert">
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

{{-- ── Kartu Statistik ── --}}
@if(auth()->user()->role === 'admin')
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="table-card h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                    <i class="bi bi-book fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Buku</div>
                    <div class="fw-bold fs-4">{{ number_format($totalBuku) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="table-card h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10">
                    <i class="bi bi-people fs-4 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Anggota</div>
                    <div class="fw-bold fs-4">{{ number_format($totalAnggota) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="table-card h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-arrow-left-right fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Sedang Dipinjam</div>
                    <div class="fw-bold fs-4">{{ number_format($dipinjam) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="table-card h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-danger bg-opacity-10">
                    <i class="bi bi-cash-stack fs-4 text-danger"></i>
                </div>
                <div>
                    <div class="text-muted small">Denda Bulan Ini</div>
                    <div class="fw-bold fs-4">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Widget Pending Approval (admin only) ── --}}
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
                    <th style="width:160px">Aksi</th>
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
                                        onclick="return confirm('Setujui permintaan ini?')" title="Setujui">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="bi bi-check-lg"></i>
                            </button>
                            @endif
                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" data-bs-target="#modalTolakDashboard"
                                    data-id="{{ $p->id }}"
                                    data-nama="{{ $p->anggota->nama ?? '' }}"
                                    data-buku="{{ $p->buku->judul }}" title="Tolak">
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

{{-- ── Grafik + Buku Terpopuler (admin only) ── --}}
@if(auth()->user()->role === 'admin')
<div class="row g-3 mb-4">
    {{-- Grafik Peminjaman 6 Bulan --}}
    <div class="col-lg-8">
        <div class="table-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-bar-chart me-2 text-primary"></i>Peminjaman 6 Bulan Terakhir
            </h6>
            <canvas id="chartBulanan" height="120"></canvas>
        </div>
    </div>

    {{-- Buku Terpopuler --}}
    <div class="col-lg-4">
        <div class="table-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-trophy me-2 text-warning"></i>Buku Terpopuler Bulan Ini
            </h6>
            @forelse($bukuTerpopuler as $i => $b)
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge rounded-pill"
                      style="background:{{ ['#0d6efd','#6f42c1','#0dcaf0','#20c997','#ffc107'][$i] ?? '#6c757d' }};
                             min-width:24px">
                    {{ $i + 1 }}
                </span>
                <div class="flex-grow-1 small">
                    <div class="fw-semibold text-truncate" style="max-width:160px">{{ $b->judul }}</div>
                    <div class="text-muted" style="font-size:.72rem">{{ $b->pengarang }}</div>
                </div>
                <span class="badge bg-light text-dark border">{{ $b->peminjaman_count }}x</span>
            </div>
            @empty
            <div class="text-muted small text-center py-3">Belum ada data bulan ini</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── Terlambat + Peminjaman Terbaru ── --}}
<div class="row g-3">
    {{-- Peminjaman Terlambat --}}
    <div class="col-lg-6">
        <div class="table-card h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Belum Dikembalikan & Terlambat
                @if($terlambat->count() > 0)
                <span class="badge bg-danger ms-1">{{ $terlambat->count() }}</span>
                @endif
            </h6>
            @forelse($terlambat as $t)
            <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                <div>
                    <div class="fw-semibold small">{{ $t->anggota->nama }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ Str::limit($t->buku->judul, 25) }}</div>
                    <div class="text-danger" style="font-size:.72rem">
                        <i class="bi bi-clock me-1"></i>
                        Jatuh tempo {{ $t->tgl_kembali_rencana->format('d/m/Y') }}
                    </div>
                </div>
                <a href="{{ route('peminjaman.show', $t->id) }}"
                   class="btn btn-sm btn-outline-danger">Detail</a>
            </div>
            @empty
            <div class="text-success small text-center py-3">
                <i class="bi bi-check-circle me-1"></i>Tidak ada peminjaman terlambat
            </div>
            @endforelse
        </div>
    </div>

    {{-- Peminjaman Terbaru --}}
    <div class="col-lg-6">
        <div class="table-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Peminjaman Terbaru
                </h6>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            @forelse($pinjamanTerbaru as $p)
            <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                <div>
                    <div class="fw-semibold small">{{ $p->anggota->nama }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ Str::limit($p->buku->judul, 25) }}</div>
                    <div class="text-muted" style="font-size:.72rem">{{ $p->created_at->diffForHumans() }}</div>
                </div>
                {!! $p->status_badge !!}
            </div>
            @empty
            <div class="text-muted small text-center py-3">Belum ada data peminjaman</div>
            @endforelse
        </div>
    </div>
</div>
@endif

{{-- ── Tampilan Anggota ── --}}
@if(auth()->user()->role === 'anggota')
<div class="row g-3">
    <div class="col-12">
        <div class="table-card text-center py-4">
            <i class="bi bi-book fs-1 text-primary mb-3 d-block"></i>
            <h5 class="fw-bold">Selamat datang di Perpustakaan SMKN</h5>
            <p class="text-muted">Temukan buku favoritmu dan ajukan peminjaman dengan mudah.</p>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <a href="{{ route('user.katalog') }}" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Cari Buku
                </a>
                <a href="{{ route('user.riwayat') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-clock-history me-1"></i>Riwayat Saya
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Modal Tolak (dashboard) ── --}}
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Modal Tolak
document.getElementById('modalTolakDashboard')?.addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('td-buku').textContent = btn.dataset.buku;
    document.getElementById('td-nama').textContent = btn.dataset.nama;
    document.getElementById('formTolakDashboard').action = '/peminjaman/' + btn.dataset.id + '/reject';
});

// Grafik Bulanan
@if(auth()->user()->role === 'admin')
const ctx = document.getElementById('chartBulanan');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($laporanBulanan->pluck('label')) !!},
            datasets: [
                {
                    label: 'Jumlah Peminjaman',
                    data: {!! json_encode($laporanBulanan->pluck('jumlah')) !!},
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderRadius: 4,
                },
                {
                    label: 'Total Denda (Rp)',
                    data: {!! json_encode($laporanBulanan->pluck('total_denda')) !!},
                    backgroundColor: 'rgba(220, 53, 69, 0.6)',
                    borderRadius: 4,
                    yAxisID: 'y2',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y:  { beginAtZero: true, title: { display: true, text: 'Jumlah' } },
                y2: { beginAtZero: true, position: 'right', title: { display: true, text: 'Denda (Rp)' },
                      grid: { drawOnChartArea: false } }
            }
        }
    });
}
@endif
</script>
@endpush