<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsMember
{
    /**
     * Handle an incoming request.
     * Member dan Admin sama-sama bisa akses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login → redirect ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Role tidak dikenali → tolak akses
        if (!in_array(Auth::user()->role, ['member', 'admin'])) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}
