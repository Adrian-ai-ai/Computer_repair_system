<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $users = User::orderBy('name')->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'technician', 'storekeeper'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing a user
     */
    public function edit(User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'technician', 'storekeeper'])],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        // Prevent deleting the current user
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account');
        }

        // Check for active references before deletion
        $stockMovementCount = DB::table('stock_movements')->where('created_by', $user->id)->count();
        $activityLogCount = DB::table('activity_logs')->where('user_id', $user->id)->count();
        $quotationCount = DB::table('quotations')->where('created_by', $user->id)->count();
        
        $totalReferences = $stockMovementCount + $activityLogCount + $quotationCount;
        
        if ($totalReferences > 0) {
            $message = "Cannot delete user '{$user->name}' because they have {$totalReferences} active references:\n";
            $message .= "- {$stockMovementCount} stock movements\n";
            $message .= "- {$activityLogCount} activity logs\n";
            $message .= "- {$quotationCount} quotations\n\n";
            $message .= "Recommendation: Consider deactivating the user instead to preserve audit trails.";
            
            return redirect()->route('users.index')->with('error', $message);
        }

        // If no references, proceed with deletion
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
            
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Deactivate the specified user
     */
    public function deactivate(User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }
        
        // Prevent deactivating the current user
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot deactivate your own account');
        }

        try {
            $user->deactivate();
            return redirect()->route('users.index')->with('success', 'User deactivated successfully. Their audit trails have been preserved.');
            
        } catch (\Exception $e) {
            Log::error('Failed to deactivate user: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to deactivate user. Please try again.');
        }
    }

    /**
     * Activate the specified user
     */
    public function activate(User $user)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        try {
            $user->activate();
            return redirect()->route('users.index')->with('success', 'User activated successfully.');
            
        } catch (\Exception $e) {
            Log::error('Failed to activate user: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to activate user. Please try again.');
        }
    }
}