<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showSetPasswordForm()
    {
        $user = Auth::user();
        if (empty($user->password)) {
        return view('auth.set-password');
        } else {
            return redirect()->intended('/');
    }
    }
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->intended('/');
    }
}
