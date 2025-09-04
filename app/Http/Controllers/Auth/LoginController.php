<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Akun tidak aktif atau tidak ditemukan'
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Log failed attempt
            Log::warning('login_failed', [
                'user_id' => $user?->id,
                'email' => $request->email,
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah'
            ]);
        }

        $request->session()->regenerate();

        // Log successful login
        Log::info('login_success', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'agent' => $request->userAgent(),
        ]);

        return $this->redirectBasedOnRole($user);
    }

    public function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case User::ROLE_ADMIN:
                return redirect()->route('admin.dashboard');
            case User::ROLE_CASHIER:
                return redirect()->route('cashier.dashboard');
            case User::ROLE_MANAGER:
                return redirect()->route('manager.dashboard');
            default:
                return redirect()->route('/');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        // Log logout
        Log::info('logout', [
            'user_id' => $user?->id,
            'ip' => $request->ip(),
            'agent' => $request->userAgent(),
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
