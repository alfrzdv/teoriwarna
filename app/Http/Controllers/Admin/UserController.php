<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // View all users
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'banned') {
                $query->where('is_banned', true);
            } else {
                $query->where('is_banned', false);
            }
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    // View user detail
    public function show(User $user)
    {
        $user->load(['orders', 'user_addresses']);

        return view('admin.users.show', compact('user'));
    }

    // Edit user form
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
            'phone' => 'nullable|string|max:20'
        ]);

        $user->update($request->only(['name', 'email', 'role', 'phone']));

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    // Ban user
    public function ban(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat memban admin.');
        }

        $user->update(['is_banned' => true]);

        return back()->with('success', 'User berhasil dibanned.');
    }

    // Unban user
    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);

        return back()->with('success', 'User berhasil di-unban.');
    }
}
