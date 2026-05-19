<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman show profil.
     * - Jika admin: bisa lihat profil siapa saja via ?user_id=x
     * - Jika anggota: hanya bisa lihat profil sendiri
     */
    public function show(Request $request, ?int $id = null): View
    {
        if ($id && auth()->user()->role === 'admin') {
            $user = User::findOrFail($id);
        } else {
            $user = $request->user();
        }

        return view('profile.show', compact('user'));
    }

    /**
     * Tampilkan halaman edit profil pengguna.
     */
    public function edit(Request $request): View
    {
        $anggota = null;

        if ($request->user()->isAnggota()) {
            $anggota = $request->user()->anggota
                ?? Anggota::where('nis', $request->user()->email)->first();
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'anggota' => $anggota,
        ]);
    }

    /**
     * Update informasi profil pengguna.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        DB::transaction(function () use ($user, $validated) {
            $user->fill($validated);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            if ($user->isAnggota()) {
                $anggota = $user->anggota ?? Anggota::where('nis', $user->email)->first();

                if ($anggota) {
                    $anggota->update(['nama' => $validated['name']]);
                }
            }
        });

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update password pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->user()->isAnggota()) {
            $anggota = $request->user()->anggota
                ?? Anggota::where('nis', $request->user()->email)->first();

            if ($anggota) {
                $anggota->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }
        }

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    public function updateAnggota(Request $request): RedirectResponse
    {
        if (!$request->user()->isAnggota()) {
            abort(403, 'Akses ditolak.');
        }

        $anggota = $request->user()->anggota
            ?? Anggota::where('nis', $request->user()->email)->first();

        if (!$anggota) {
            return Redirect::route('profile.edit')
                ->with('error', 'Data anggota tidak ditemukan. Hubungi admin.');
        }

        $validated = $request->validate([
            'kelas'  => ['required', 'string', 'max:50'],
            'no_hp'  => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ]);

        $anggota->update($validated);

        return Redirect::route('profile.edit')->with('status', 'anggota-updated');
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
