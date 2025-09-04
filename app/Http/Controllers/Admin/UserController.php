<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = [
            User::ROLE_ADMIN => 'admin',
            User::ROLE_CASHIER => 'cashier',
            User::ROLE_MANAGER => 'manager',
        ];

        return view('admin.users.create', compact('roles'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier,manager',
            'is_active' => 'sometimes|boolean',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'is_active' => $request->has('is_active') ? $validated['is_active'] : true,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {

        if ($user->is_admin) {
            abort(403, 'Admin tidak dapat mengedit');
        }

        $roles = [
            User::ROLE_CASHIER => 'cashier',
            User::ROLE_MANAGER => 'manager',
        ];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {

        if ($user->is_admin) {
            abort(403, 'Admin tidak dapat mengedit');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:cashier,manager',
            'is_active' => 'sometimes|boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        if ($user->is_admin) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus akun admin.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
