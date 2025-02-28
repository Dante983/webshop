<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);
        
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['is_admin'] = $request->has('is_admin');
        
        User::create($validatedData);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function show(User $user): View
    {
        $user->load('orders');
        
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);
        
        if ($validatedData['password']) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        
        $validatedData['is_admin'] = $request->has('is_admin');
        
        $user->update($validatedData);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account');
        }
        
        $user->delete();
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
} 