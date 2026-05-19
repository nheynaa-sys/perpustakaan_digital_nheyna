@extends('layouts.app')

@section('title', 'Edit Anggota')
@section('breadcrumb', 'Anggota › Edit')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Anggota</h4>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="form-card">
    <form action="{{ route('anggota.update', $anggota->id) }}" method="POST">
        @csrf @method('PATCH')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">NIS <span class="text-danger">*</span></label>
                <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror"
                       value="{{ old('nis', $anggota->nis) }}" required>
                @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                <input type="text" name="kelas" class="form-control"
                       value="{{ old('kelas', $anggota->kelas) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $anggota->nama) }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control"
                       value="{{ old('no_hp', $anggota->no_hp) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $anggota->alamat) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Password Baru
                    <span class="text-muted small">(kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 6 karakter">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-warning px-4">
                <i class="bi bi-save me-1"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
</div>
</div>
@endsection