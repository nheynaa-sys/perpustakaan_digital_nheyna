@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('breadcrumb', 'Kategori › Tambah')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0"><i class="bi bi-tag me-2 text-primary"></i>Tambah Kategori</h4>
</div>
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="form-card">
    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror"
                   value="{{ old('nama_kategori') }}" placeholder="Contoh: Fiksi, Sains, Sejarah" required>
            @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"
                      placeholder="Deskripsi kategori...">{{ old('deskripsi') }}</textarea>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan</button>
        </div>
    </form>
</div>
</div>
</div>
@endsection