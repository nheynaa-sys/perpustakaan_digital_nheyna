@extends('layouts.app')

@section('title', 'Katalog Buku')
@section('breadcrumb', 'Katalog')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-grid me-2 text-primary"></i>Katalog Buku</h4>
            <p class="text-muted mb-0 small">Temukan dan ajukan peminjaman buku perpustakaan</p>
        </div>
        <a href="{{ route('user.riwayat') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-clock-history me-1"></i>Riwayat Pinjam Saya
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('error') }}</div>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(!$anggota)
        <div class="alert alert-warning d-flex align-items-center gap-2">
            <i class="bi bi-person-exclamation fs-5"></i>
            <div>Akun Anda belum terdaftar sebagai <strong>anggota perpustakaan</strong>. Hubungi admin untuk mendaftarkan diri.
            </div>
        </div>
    @endif

    {{-- Filter & Pencarian --}}
    <div class="table-card mb-4">
        <form method="GET" action="{{ route('user.katalog') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Cari Buku</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-primary"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Judul atau pengarang..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Kategori</label>
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
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
                <a href="{{ route('user.katalog') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Grid Buku --}}
    <div class="row g-3">
        @forelse($buku as $b)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm book-card">
                    {{-- Cover --}}
                    <div class="book-cover-wrap">
                        @if($b->cover)
                            <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->judul }}" class="book-cover-img">
                        @else
                            <div class="book-cover-placeholder">
                                <i class="bi bi-book"></i>
                            </div>
                        @endif

                        {{-- Badge stok --}}
                        <div class="book-stock-badge">
                            @if($b->stok > 5)
                                <span class="badge bg-success">{{ $b->stok }} tersedia</span>
                            @elseif($b->stok > 0)
                                <span class="badge bg-warning text-dark">{{ $b->stok }} tersisa</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="book-title mb-1">{{ $b->judul }}</h6>
                        <p class="text-muted small mb-2">{{ $b->pengarang }}</p>

                        {{-- Kategori --}}
                        <div class="mb-auto">
                            @foreach($b->kategori as $k)
                                <span class="badge bg-info text-dark me-1" style="font-size:.7rem">{{ $k->nama_kategori }}</span>
                            @endforeach
                        </div>

                        {{-- Tombol aksi --}}
                        <div class="mt-3">
                            @if(!$anggota)
                                <button class="btn btn-sm btn-secondary w-100" disabled>
                                    <i class="bi bi-lock me-1"></i>Belum Terdaftar
                                </button>
                            @elseif($bukuDipinjam->contains($b->id_buku))
                                <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                                    <i class="bi bi-hourglass-split me-1"></i>Sudah Diajukan
                                </button>
                            @elseif($b->stok <= 0)
                                <button class="btn btn-sm btn-outline-danger w-100" disabled>
                                    <i class="bi bi-x-circle me-1"></i>Stok Habis
                                </button>
                            @else
                                <button class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalPinjam"
                                    data-buku-id="{{ $b->id_buku }}" data-buku-judul="{{ $b->judul }}">
                                    <i class="bi bi-bookmark-plus me-1"></i>Ajukan Pinjam
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                    <p>Tidak ada buku ditemukan.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($buku->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Menampilkan {{ $buku->firstItem() }}–{{ $buku->lastItem() }} dari {{ $buku->total() }} buku
            </div>
            {{ $buku->links() }}
        </div>
    @endif 

    {{-- Modal Ajukan Pinjam --}}
    <div class="modal fade" id="modalPinjam" tabindex="-1" aria-labelledby="modalPinjamLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="modalPinjamLabel">
                            <i class="bi bi-bookmark-plus text-primary me-2"></i>Ajukan Peminjaman
                        </h5>
                        <p class="text-muted small mb-0">Permintaan akan dikirim ke admin untuk disetujui</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('user.ajukan') }}">
                    @csrf
                    <div class="modal-body pt-3">

                        <div class="alert alert-info d-flex gap-2 align-items-start py-2 mb-3">
                            <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                            <div class="small">
                                Setelah diajukan, permintaan Anda akan masuk ke antrian admin.
                                Buku akan tersedia setelah admin menyetujui.
                            </div>
                        </div>

                        <input type="hidden" name="buku_id" id="input-buku-id">

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Buku</label>
                            <input type="text" class="form-control" id="display-buku-judul" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold" for="tgl_kembali_rencana">
                                Rencana Tanggal Kembali <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" name="tgl_kembali_rencana" id="tgl_kembali_rencana"
                                min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}"
                                max="{{ \Carbon\Carbon::today()->addDays(30)->toDateString() }}" required>
                            <div class="form-text">Maksimal peminjaman 30 hari dari sekarang.</div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .book-card {
            border-radius: 12px !important;
            transition: transform .2s, box-shadow .2s;
            overflow: hidden;
        }

        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(24, 95, 165, .15) !important;
        }

        .book-cover-wrap {
            position: relative;
            height: 180px;
            background: #E6F1FB;
            overflow: hidden;
        }

        .book-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-cover-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #B5D4F4;
        }

        .book-stock-badge {
            position: absolute;
            top: 8px;
            right: 8px;
        }

        .book-title {
            font-size: .9rem;
            font-weight: 600;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('modalPinjam');
            modal.addEventListener('show.bs.modal', function (e) {
                const btn = e.relatedTarget;
                document.getElementById('input-buku-id').value = btn.dataset.bukuId;
                document.getElementById('display-buku-judul').value = btn.dataset.bukuJudul;
            });
        });
    </script>
@endpush