@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('breadcrumb', 'Buku › Tambah')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0"><i class="bi bi-book-fill me-2 text-primary"></i>Tambah Buku Baru</h4>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="form-card">
    <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            {{-- Judul --}}
            <div class="col-12">
                <label class="form-label">Judul Buku <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                       value="{{ old('judul') }}" placeholder="Masukkan judul buku" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Pengarang --}}
            <div class="col-md-6">
                <label class="form-label">Pengarang <span class="text-danger">*</span></label>
                <input type="text" name="pengarang" class="form-control @error('pengarang') is-invalid @enderror"
                       value="{{ old('pengarang') }}" placeholder="Nama pengarang" required>
                @error('pengarang')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Penerbit --}}
            <div class="col-md-6">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
                       value="{{ old('penerbit') }}" placeholder="Nama penerbit">
                @error('penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Tahun Terbit --}}
            <div class="col-md-4">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror"
                       value="{{ old('tahun_terbit') }}" placeholder="2024" min="1900" max="{{ date('Y') }}">
                @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- ISBN --}}
            <div class="col-md-4">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                       value="{{ old('isbn') }}" placeholder="978-xxxxxxxxxx">
                @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Stok --}}
            <div class="col-md-4">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                       value="{{ old('stok', 0) }}" min="0" required>
                @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Kategori --}}
            <div class="col-12">
                <label class="form-label">Kategori <small class="text-muted">(bisa lebih dari satu)</small></label>
                <div class="border rounded p-3 d-flex flex-wrap gap-3">
                    @foreach($kategoris as $k)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="kategori_id[]"
                               value="{{ $k->id }}" id="kat_{{ $k->id }}"
                               {{ in_array($k->id, old('kategori_id', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kat_{{ $k->id }}">{{ $k->nama_kategori }}</label>
                    </div>
                    @endforeach
                    @if($kategoris->isEmpty())
                    <span class="text-muted small">Belum ada kategori. <a href="{{ route('kategori.create') }}">Tambah dulu →</a></span>
                    @endif
                </div>
                @error('kategori_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- Cover --}}
            <div class="col-12">
                <label class="form-label">Cover Buku</label>
                <input type="file" name="cover" id="coverInput"
                       class="form-control @error('cover') is-invalid @enderror"
                       accept="image/*" onchange="previewCover(this)">
                <div class="form-text">Format: JPG, PNG, WebP. Maks 2 MB.</div>
                @error('cover')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <img id="coverPreview" src="#" alt="Preview" class="mt-2 rounded d-none"
                     style="max-height:120px; object-fit:cover">
            </div>

            {{-- Deskripsi --}}
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"
                          placeholder="Deskripsi singkat buku...">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <hr class="my-4">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Simpan Buku
            </button>
        </div>
    </form>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
function previewCover(input) {
    const preview = document.getElementById('coverPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush