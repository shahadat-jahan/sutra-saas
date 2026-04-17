<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(): Response
    {
        $shop = auth()->user()->shop;
        
        return Inertia::render('Tenant/Users/Index', [
            'users' => $shop->users()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'required|string|in:shop_owner,staff',
        ]);

        $shop = auth()->user()->shop;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'shop_id' => $shop->id,
        ]);

        // Set Team Context for Spatie Permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($shop->id);

        $user->assignRole($request->role);

        return back()->with('success', 'User created successfully.');
    }

    public function destroy(User $user)
    {
        // Ensure user belongs to the same shop
        if ($user->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
