<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarStoredPath = null;
        if ($request->hasFile('avatar')) {
            // store file to public disk
            $avatarStoredPath = $request->file('avatar')->store('avatars', 'public');

            // attempt to delete old avatar only if column exists and user has one
            if (Schema::hasColumn('users', 'avatar') && $user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // only set avatar in validated array if DB has column
            if (Schema::hasColumn('users', 'avatar')) {
                $validated['avatar'] = $avatarStoredPath;
            }
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Prevent role change from profile form
        unset($validated['role']);

        $user->update($validated);

        // If avatar was stored but DB doesn't have column, show warning
        if ($avatarStoredPath && !Schema::hasColumn('users', 'avatar')) {
            return redirect()->route('profile')
                ->with('success', 'Profil berhasil diperbarui (avatar disimpan di storage).')
                ->with('warning', 'Kolom `avatar` tidak ditemukan di tabel users. Untuk menyimpan avatar ke DB, tambahkan kolom `avatar` pada tabel users.');
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
