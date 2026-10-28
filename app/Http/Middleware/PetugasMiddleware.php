<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isPetugas()) {
            abort(403, 'Akses ditolak. Hanya petugas perpustakaan yang dapat mengakses halaman ini.');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        return $next($request);
    }
}
