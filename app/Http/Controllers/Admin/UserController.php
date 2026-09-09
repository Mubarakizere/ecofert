<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of all users with search and role filtering.
     */
    public function index(Request $request): View
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $roleFilter = $request->input('role');
            $query->where('role_type', $roleFilter);
        }

        if ($request->filled('status')) {
            $statusFilter = $request->input('status');
            $query->where('status', $statusFilter);
        }

        $users = $query->get();
        $roles = Role::orderBy('name')->pluck('name');

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name')->pluck('name', 'id');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role_type' => $validated['role'],
            'password'  => Hash::make($validated['password']),
            'status'    => 'active',
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User "' . $user->name . '" created successfully with the ' . $validated['role'] . ' role.');
    }

    /**
     * Update an existing user's role assignment.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role_type' => ['required', 'string', Rule::in(['Household', 'Extension Officer', 'Admin'])],
        ]);

        $user->role_type = $validated['role_type'];
        $user->save();

        $user->syncRoles([$validated['role_type']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Role for {$user->name} updated to {$validated['role_type']}.");
    }

    /**
     * Toggle a user account's status between active and suspended.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot suspend your own account.');
        }

        $user->status = $user->status === 'suspended' ? 'active' : 'suspended';
        $user->save();

        $action = $user->status === 'suspended' ? 'suspended' : 'reactivated';

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User account '{$user->name}' has been {$action} successfully.");
    }

    /**
     * Delete a user account from the system.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User account '{$userName}' has been deleted successfully.");
    }
}
