<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // Tampilkan form login (satu view untuk admin & anggota)
    // ──────────────────────────────────────────────────────────────

    public function create(): View
    {
        return view('auth.login');
    }

    public function createAnggota(): View
    {
        return view('auth.login');
    }

    // ──────────────────────────────────────────────────────────────
    // Proses login — LoginRequest mendeteksi field 'nis' atau 'email'
    // ──────────────────────────────────────────────────────────────

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Redirect berdasarkan role dari database, bukan dari input form
        if (Auth::user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('user.katalog'));
    }

    public function storeAnggota(LoginRequest $request): RedirectResponse
    {
        $request->merge(['role' => 'anggota']);

        return $this->store($request);
    }

    // ──────────────────────────────────────────────────────────────
    // Logout
    // ──────────────────────────────────────────────────────────────

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
