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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (! $request->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda sedang dinonaktifkan. Hubungi pustakawan.');
        }

        if (! in_array($request->user()->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}

