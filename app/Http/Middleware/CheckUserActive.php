<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                // Log forced logout due to inactive status
                Log::info('logout_inactive', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                    'agent' => $request->userAgent(),
                ]);

                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.']);
            }
        }

        return $next($request);
    }
}