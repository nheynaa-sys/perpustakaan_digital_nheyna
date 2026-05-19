{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil ' . $user->name)

@section('content')
<div class="container py-4">

    {{-- ===== BREADCRUMB ===== --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                    <i class="bi bi-house me-1"></i>Dashboard
                </a>
            </li>
            @if(auth()->user()->role === 'admin')
            <li class="breadcrumb-item">
                <a href="{{ route('anggota.index') }}" class="text-decoration-none">Anggota</a>
            </li>
            @endif
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
    </nav>

    {{-- ===== HERO CARD (tanpa banner) ===== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-4">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-4">

                {{-- Avatar --}}
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow"
                     style="width:80px; height:80px; font-size:2rem; font-weight:700; color:#fff;
                            background: {{ $user->role === 'admin' ? '#dc3545' : '#0d6efd' }};">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                {{-- Nama & badge --}}
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-2">{{ $user->name }}</h4>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} px-3 py-2">
                            <i class="bi bi-person-fill me-1"></i>{{ ucfirst($user->role) }}
                        </span>
                        @if($user->email_verified_at)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="bi bi-patch-check-fill me-1"></i>Terverifikasi
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                <i class="bi bi-exclamation-circle me-1"></i>Belum Terverifikasi
                            </span>
                        @endif
                        @if(auth()->id() === $user->id)
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                <i class="bi bi-person-check me-1"></i>Anda
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Tombol Edit --}}
                @if(auth()->id() === $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm flex-shrink-0">
                        <i class="bi bi-pencil me-1"></i>Edit Profil
                    </a>
                @elseif(auth()->user()->role === 'admin')
                    <a href="{{ route('anggota.edit', $user->id) }}" class="btn btn-outline-secondary btn-sm flex-shrink-0">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                @endif

            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ===== KOLOM KIRI ===== --}}
        <div class="col-lg-4">

            {{-- Info Kontak --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom fw-semibold">
                    <i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Akun
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-3 py-3">
                            <div class="text-muted small mb-1">
                                <i class="bi bi-envelope me-1"></i>Email
                            </div>
                            <div class="fw-semibold text-break">{{ $user->email }}</div>
                        </li>
                        <li class="list-group-item px-3 py-3">
                            <div class="text-muted small mb-1">
                                <i class="bi bi-shield-fill me-1"></i>Role
                            </div>
                            <div class="fw-semibold">{{ ucfirst($user->role) }}</div>
                        </li>
                        <li class="list-group-item px-3 py-3">
                            <div class="text-muted small mb-1">
                                <i class="bi bi-calendar-plus me-1"></i>Bergabung Sejak
                            </div>
                            <div class="fw-semibold">{{ $user->created_at->format('d F Y') }}</div>
                            <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                        </li>
                        <li class="list-group-item px-3 py-3">
                            <div class="text-muted small mb-1">
                                <i class="bi bi-clock-history me-1"></i>Terakhir Diperbarui
                            </div>
                            <div class="fw-semibold">{{ $user->updated_at->format('d F Y') }}</div>
                            <div class="text-muted small">{{ $user->updated_at->diffForHumans() }}</div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Statistik --}}
            @if($user->role === 'anggota')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom fw-semibold">
                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Statistik Peminjaman
                </div>
                <div class="card-body p-3">
                    @php
                        $total      = $user->peminjamans()->count();
                        $dipinjam   = $user->peminjamans()->where('status', 'dipinjam')->count();
                        $kembali    = $user->peminjamans()->where('status', 'dikembalikan')->count();
                        $terlambat  = $user->peminjamans()->where('status', 'terlambat')->count();
                    @endphp

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center" style="background:#e7f1ff;">
                                <div class="fw-bold fs-4 text-primary">{{ $total }}</div>
                                <div class="text-muted small">Total</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center" style="background:#fff3cd;">
                                <div class="fw-bold fs-4 text-warning">{{ $dipinjam }}</div>
                                <div class="text-muted small">Dipinjam</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center" style="background:#d1e7dd;">
                                <div class="fw-bold fs-4 text-success">{{ $kembali }}</div>
                                <div class="text-muted small">Dikembalikan</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center" style="background:#f8d7da;">
                                <div class="fw-bold fs-4 text-danger">{{ $terlambat }}</div>
                                <div class="text-muted small">Terlambat</div>
                            </div>
                        </div>
                    </div>

                    @if($total > 0)
                    @php $pct = round(($kembali / $total) * 100); @endphp
                    <div class="mt-1">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Tingkat Pengembalian</span>
                            <span class="fw-semibold">{{ $pct }}%</span>
                        </div>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-{{ $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger') }}"
                                 style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- end kolom kiri --}}

        {{-- ===== KOLOM KANAN: RIWAYAT PEMINJAMAN ===== --}}
        <div class="col-lg-8">
            @if($user->role === 'anggota')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Peminjaman
                    </span>
                    @if(auth()->id() === $user->id || auth()->user()->role === 'admin')
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    @endif
                </div>

                @php
                    $riwayat = $user->peminjamans()
                        ->with('buku')
                        ->latest()
                        ->take(8)
                        ->get();
                @endphp

                @if($riwayat->isEmpty())
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                        <p class="text-muted mb-0">Belum ada riwayat peminjaman.</p>
                        @if(auth()->id() === $user->id)
                        <a href="{{ route('buku.index') }}" class="btn btn-primary btn-sm mt-3">
                            <i class="bi bi-search me-1"></i>Cari Buku
                        </a>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Judul Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayat as $i => $p)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $p->buku->judul ?? '-' }}</div>
                                        <small class="text-muted">{{ $p->buku->pengarang ?? '' }}</small>
                                    </td>
                                    <td class="small">
                                        {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
                                    </td>
                                    <td class="small">
                                        @if($p->tanggal_kembali)
                                            {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeMap = ['dipinjam' => 'warning', 'dikembalikan' => 'success', 'terlambat' => 'danger'];
                                            $iconMap  = ['dipinjam' => 'hourglass-split', 'dikembalikan' => 'check2-circle', 'terlambat' => 'exclamation-circle'];
                                            $color    = $badgeMap[$p->status] ?? 'secondary';
                                            $icon     = $iconMap[$p->status]  ?? 'dash-circle';
                                        @endphp
                                        <span class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle px-2">
                                            <i class="bi bi-{{ $icon }} me-1"></i>{{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('peminjaman.show', $p->id) }}"
                                           class="btn btn-sm btn-outline-secondary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($riwayat->count() >= 8)
                    <div class="card-footer bg-transparent text-center border-top py-2">
                        <a href="{{ route('peminjaman.index') }}" class="text-decoration-none small text-muted">
                            Tampilkan semua riwayat <i class="bi bi-chevron-down ms-1"></i>
                        </a>
                    </div>
                    @endif
                @endif
            </div>
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom fw-semibold">
                    <i class="bi bi-info-circle me-2 text-primary"></i>Informasi
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-shield-check display-4 text-primary d-block mb-3"></i>
                    <h6 class="fw-bold">Akun Administrator</h6>
                    <p class="text-muted small mb-0">
                        Akun ini memiliki akses penuh untuk mengelola buku, anggota, kategori, dan peminjaman.
                    </p>
                </div>
            </div>
            @endif
        </div>{{-- end kolom kanan --}}

    </div>
</div>
@endsection