<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;



class UserController extends Controller
{

    /**
     * Show form to edit a user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,cashier',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Show form to create a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,cashier',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
    /**
     * Display all users.
     */
    public function index()
    {
        $users = User::all()->map(function($user) {
            return [
                'user' => $user,
                'last_login' => UserLog::where('user_id', $user->id)
                    ->where('action', 'login')
                    ->latest()
                    ->first()
            ];
        });

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display user activity log.
     */
    public function activity(Request $request)
    {
        $user_id = $request->get('user_id');

        $logs = UserLog::query();

        if ($user_id) {
            $logs->where('user_id', $user_id);
        }

        $logs = $logs->with('user')
            ->latest()
            ->paginate(20);

        $users = User::all();

        return view('admin.users.activity', compact('logs', 'users', 'user_id'));
    }
}
