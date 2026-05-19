@extends('layouts.app')

@section('title', 'Tambah Peminjaman')
@section('breadcrumb', 'Peminjaman › Tambah')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0"><i class="bi bi-bookmark-plus me-2 text-primary"></i>Form Peminjaman Buku</h4>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="form-card">
    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            {{-- Pilih Anggota --}}
            <div class="col-12">
                <label class="form-label">Anggota <span class="text-danger">*</span></label>
                <select name="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($anggota as $a)
                    <option value="{{ $a->id }}" {{ old('anggota_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->nama }} — {{ $a->kelas }} (NIS: {{ $a->nis }})
                    </option>
                    @endforeach
                </select>
                @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Pilih Buku --}}
            <div class="col-12">
                <label class="form-label">Buku <span class="text-danger">*</span></label>
                <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Buku (stok tersedia) --</option>
                    @foreach($buku as $b)
                    <option value="{{ $b->id_buku }}" {{ old('buku_id') == $b->id_buku ? 'selected' : '' }}>
                        {{ $b->judul }} — {{ $b->pengarang }} (Stok: {{ $b->stok }})
                    </option>
                    @endforeach
                </select>
                @error('buku_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($buku->isEmpty())
                <div class="text-warning small mt-1"><i class="bi bi-exclamation-triangle me-1"></i>Tidak ada buku dengan stok tersedia.</div>
                @endif
            </div>

            {{-- Tanggal Pinjam --}}
            <div class="col-md-6">
                <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                <input type="date" name="tgl_pinjam"
                       class="form-control @error('tgl_pinjam') is-invalid @enderror"
                       value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required>
                @error('tgl_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Tanggal Kembali Rencana --}}
            <div class="col-md-6">
                <label class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
                <input type="date" name="tgl_kembali_rencana"
                       class="form-control @error('tgl_kembali_rencana') is-invalid @enderror"
                       value="{{ old('tgl_kembali_rencana', date('Y-m-d', strtotime('+7 days'))) }}" required>
                @error('tgl_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="alert alert-info mt-3 small">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Ketentuan:</strong> Denda keterlambatan Rp 1.000 per hari. Anggota tidak boleh meminjam buku yang sama sebelum dikembalikan.
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i>Simpan Peminjaman
            </button>
        </div>
    </form>
</div>
</div>
</div>
@endsection