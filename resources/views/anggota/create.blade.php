@extends('layouts.app')

@section('title', 'Tambah Anggota')
@section('breadcrumb', 'Anggota › Tambah')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Tambah Anggota</h4>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="form-card">
    <form action="{{ route('anggota.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">NIS <span class="text-danger">*</span></label>
                <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror"
                       value="{{ old('nis') }}" placeholder="Nomor Induk Siswa" required>
                @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror"
                       value="{{ old('kelas') }}" placeholder="XI RPL 1" required>
                @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}" placeholder="Nama lengkap anggota" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control"
                       value="{{ old('no_hp') }}" placeholder="08xx-xxxx-xxxx">
            </div>
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Kosongkan untuk memakai NIS">
                <div class="form-text">Jika kosong, password awal akan sama dengan NIS.</div>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Ulangi password">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"
                          placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i>Simpan
            </button>
        </div>
    </form>
</div>
</div>
</div>
@endsection
