<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $users = User::where('id','!=',Auth::id())->where('email','!=','superAdmin@gmail.com')->orderBy('created_at', 'desc')->paginate(10);
        return view('user-management.index', compact('users'));

    }

    public function create()
    {
        return view('user-management.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('user-management.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        return view('user-management.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('user-management.index')
            ->with('success', 'User updated successfully!');
    }

    public function updatePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user-management.index')
            ->with('success', 'Password updated successfully!');
    }

    public function toggleBlock(Request $request, User $user)
    {
        $previousStatus = $user->is_blocked; // 0 or 1

        $user->update([
            'blocked_at' => $previousStatus ? null : now(),
            'is_blocked' => !$previousStatus,
        ]);

        $status = $previousStatus ? 'unblocked' : 'blocked';

        return redirect()->route('user-management.index')
            ->with('success', "User {$status} successfully!");
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user-management.index')
            ->with('success', 'User deleted successfully!');
    }
}
