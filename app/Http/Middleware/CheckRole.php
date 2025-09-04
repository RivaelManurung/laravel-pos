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
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user is active
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.']);
        }

        // Check role access
        if ($user->role !== $role) {
            abort(403, 'Akses tidak diizinkan untuk role Anda.');
        }

        return $next($request);
    }
}