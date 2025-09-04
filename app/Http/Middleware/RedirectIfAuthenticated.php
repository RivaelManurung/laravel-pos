<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Check if user is active
                if (!$user->is_active) {
                    // Log forced logout due to inactive
                    Log::info('logout_inactive', [
                        'user_id' => $user->id,
                        'ip' => $request->ip(),
                        'agent' => $request->userAgent(),
                    ]);

                    Auth::guard($guard)->logout();
                    return redirect()->route('login')
                        ->withErrors(['email' => 'Akun Anda dinonaktifkan.']);
                }

                // Log redirect-based login
                Log::info('redirect_authenticated', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                    'agent' => $request->userAgent(),
                ]);

                // Redirect based on role
                return $this->redirectBasedOnRole($user);
            }
        }

        return $next($request);
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case User::ROLE_ADMIN:
                return redirect()->route('admin.dashboard');
            case User::ROLE_CASHIER:
                return redirect()->route('cashier.pos');
            case User::ROLE_MANAGER:
                return redirect()->route('manager.dashboard');
            default:
                return redirect('/');
        }
    }
}