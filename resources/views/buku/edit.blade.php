@extends('layouts.app')

@section('title', 'Edit Buku')
@section('breadcrumb', 'Buku › Edit')

@section('content')
<div class="page-header d-flex align-items-center gap-2">
    <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Buku</h4>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="form-card">
    <form action="{{ route('buku.update', $buku->id_buku) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PATCH')

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Judul Buku <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                       value="{{ old('judul', $buku->judul) }}" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Pengarang <span class="text-danger">*</span></label>
                <input type="text" name="pengarang" class="form-control @error('pengarang') is-invalid @enderror"
                       value="{{ old('pengarang', $buku->pengarang) }}" required>
                @error('pengarang')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control"
                       value="{{ old('penerbit', $buku->penerbit) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control"
                       value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900" max="{{ date('Y') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                       value="{{ old('isbn', $buku->isbn) }}">
                @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                       value="{{ old('stok', $buku->stok) }}" min="0" required>
                @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Kategori</label>
                <div class="border rounded p-3 d-flex flex-wrap gap-3">
                    @foreach($kategoris as $k)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="kategori_id[]"
                               value="{{ $k->id }}" id="kat_{{ $k->id }}"
                               {{ in_array($k->id, old('kategori_id', $selectedKategori)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kat_{{ $k->id }}">{{ $k->nama_kategori }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Cover Buku</label>
                @if($buku->cover)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover saat ini"
                         class="rounded" style="height:80px;object-fit:cover">
                    <div class="text-muted small">Cover saat ini</div>
                </div>
                @endif
                <input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror"
                       accept="image/*" onchange="previewCover(this)">
                <div class="form-text">Biarkan kosong jika tidak ingin mengubah cover.</div>
                @error('cover')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <img id="coverPreview" src="#" class="mt-2 rounded d-none" style="max-height:100px">
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
            </div>
        </div>

        <hr class="my-4">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-warning px-4">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
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
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('d-none'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush