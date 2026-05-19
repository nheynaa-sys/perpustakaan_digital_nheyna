<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Contoh pemakaian di route:
     *   ->middleware('role:admin')
     *   ->middleware('role:anggota')
     *   ->middleware('role:admin,petugas')   ← multi-role
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (! in_array($userRole, $roles)) {
            // Kembalikan ke halaman yang sesuai role-nya
            if ($userRole === 'anggota') {
                return redirect()->route('user.katalog')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}