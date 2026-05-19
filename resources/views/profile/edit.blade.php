@extends('layouts.app')

@section('title', 'Edit Profil')
@section('breadcrumb', 'Profil')

@section('content')
<div class="container py-4">
<div class="row justify-content-center">
<div class="col-md-6">

    <h5 class="fw-bold mb-4">
        <i class="bi bi-person-circle me-2 text-primary"></i>Edit Profil
    </h5>

    @if(session('status') === 'profile-updated')
        <div class="alert alert-success py-2 small d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> Profil berhasil diperbarui.
        </div>
    @endif
    @if(session('status') === 'password-updated')
        <div class="alert alert-success py-2 small d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> Password berhasil diperbarui.
        </div>
    @endif
    @if(session('status') === 'anggota-updated')
        <div class="alert alert-success py-2 small d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> Data anggota berhasil diperbarui.
        </div>
    @endif

    {{-- ══════════════════════════════════════════ --}}
    {{-- INFO AKUN (sama untuk admin & anggota)    --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="form-card mb-4">
        <p class="fw-semibold text-muted small mb-3 text-uppercase letter-spacing-1">
            <i class="bi bi-person me-1"></i>Informasi Akun
        </p>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}"
                       placeholder="Nama lengkap">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Admin: tampilkan field email --}}
            @if(auth()->user()->role === 'admin')
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}"
                       placeholder="email@gmail.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif

            {{-- Anggota: tampilkan NIS (readonly) --}}
            @if(auth()->user()->role === 'anggota')
            <div class="mb-3">
                <label class="form-label">NIS</label>
                <input type="text" class="form-control bg-light"
                       value="{{ $anggota->nis ?? '-' }}" readonly>
                <div class="form-text">NIS tidak dapat diubah.</div>
            </div>
            @endif

            <button class="btn btn-primary w-100">
                <i class="bi bi-save me-1"></i>Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- DATA ANGGOTA (hanya untuk role anggota)   --}}
    {{-- ══════════════════════════════════════════ --}}
    @if(auth()->user()->role === 'anggota')
    <div class="form-card mb-4">
        <p class="fw-semibold text-muted small mb-3 text-uppercase">
            <i class="bi bi-person-badge me-1"></i>Data Anggota
        </p>

        <form method="POST" action="{{ route('profile.anggota.update') }}">
            @csrf @method('PATCH')

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <input type="text" name="kelas"
                       class="form-control @error('kelas') is-invalid @enderror"
                       value="{{ old('kelas', $anggota->kelas ?? '') }}"
                       placeholder="Contoh: XII RPL 1">
                @error('kelas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">No. HP</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                    <input type="text" name="no_hp"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp', $anggota->no_hp ?? '') }}"
                           placeholder="08xxxxxxxxxx">
                </div>
                @error('no_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" rows="3"
                          class="form-control @error('alamat') is-invalid @enderror"
                          placeholder="Alamat lengkap">{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-outline-primary w-100">
                <i class="bi bi-save me-1"></i>Simpan Data Anggota
            </button>
        </form>
    </div>
    @endif

    {{-- ══════════════════════════════════════════ --}}
    {{-- UBAH PASSWORD                             --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="form-card mb-4">
        <p class="fw-semibold text-muted small mb-3 text-uppercase">
            <i class="bi bi-lock me-1"></i>Ubah Password
        </p>

        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label">Password Saat Ini</label>
                <input type="password" name="current_password"
                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                       placeholder="••••••••">
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password"
                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                       placeholder="••••••••">
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                       class="form-control" placeholder="••••••••">
            </div>

            <button class="btn btn-outline-secondary w-100">
                <i class="bi bi-shield-lock me-1"></i>Perbarui Password
            </button>
        </form>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- HAPUS AKUN                                --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="text-center mb-4">
        <button class="btn btn-link text-danger btn-sm"
                data-bs-toggle="modal" data-bs-target="#modalHapus">
            <i class="bi bi-trash me-1"></i>Hapus akun saya
        </button>
    </div>

</div>
</div>
</div>

{{-- Modal Hapus Akun --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body p-4">
                <p class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle text-danger me-1"></i>Hapus akun?</p>
                <p class="text-muted small mb-3">Tindakan ini tidak dapat dibatalkan.</p>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf @method('DELETE')
                    <input type="password" name="password"
                           class="form-control form-control-sm mb-3"
                           placeholder="Masukkan password">
                    @error('password', 'userDeletion')
                        <p class="text-danger small mb-2">{{ $message }}</p>
                    @enderror
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm flex-fill">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
