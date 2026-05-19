<?php

namespace App\Http\Requests\Auth;

use App\Models\Anggota;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isAnggotaLogin()) {
            return [
                'nis'      => ['required', 'string'],
                'password' => ['required', 'string'],
            ];
        }

        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if ($this->isAnggotaLogin()) {
            $this->authenticateByNis();  // ✅ cek dari tabel anggota
        } else {
            $this->authenticateByEmail(); // ✅ cek dari tabel users
        }

        RateLimiter::clear($this->throttleKey());
    }

    // ✅ Login anggota — password dicek dari tabel anggota
    protected function authenticateByNis(): void
    {
        $nis = trim($this->input('nis'));

        $anggota = Anggota::where('nis', $nis)->first();

        if (! $anggota) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'nis' => 'NIS tidak ditemukan.',
            ]);
        }

        if (! Hash::check($this->input('password'), $anggota->password)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'nis' => 'Kata sandi salah.',
            ]);
        }

        if (! $anggota->user_id) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'nis' => 'Akun belum terhubung ke sistem login.',
            ]);
        }

        // Login via user yang terhubung ke anggota
        Auth::loginUsingId($anggota->user_id, $this->boolean('remember'));

        if (Auth::user()->role !== 'anggota') {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'nis' => 'Akun ini bukan akun anggota.',
            ]);
        }
    }

    // ✅ Login admin — password dicek dari tabel users
    protected function authenticateByEmail(): void
    {
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Akun ini bukan akun admin.',
            ]);
        }
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            $this->isAnggotaLogin() ? 'nis' : 'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        $identifier = $this->isAnggotaLogin()
            ? Str::transliterate(Str::lower($this->string('nis')))
            : Str::transliterate(Str::lower($this->string('email')));

        return $identifier . '|' . $this->ip();
    }

    protected function isAnggotaLogin(): bool
    {
        return $this->input('role') === 'anggota'
            || $this->routeIs('login.anggota.submit')
            || $this->has('nis');
    }
}
