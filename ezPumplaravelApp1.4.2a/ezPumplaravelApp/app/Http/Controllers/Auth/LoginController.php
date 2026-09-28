<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        // $this->middleware('auth')->only('logout');
    }

    protected function attemptLoginFunction(Request $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && ($user->password === null || $user->password === '')) {
            Auth::login($user);
            return redirect()->route('password.set');
        }else{
        // reroute to another function login
        return $this->login($request);

        }

    }

    public function showLoginFormold()
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        return view('auth.login', ['loggedInUser' => $user]);
    }
    public function showLoginForm()
    {
        // ✅ Check if database exists before showing login
        try {
            \DB::connection()->getPdo();
        } catch (\Throwable $t) {
            // ❌ Database not available → redirect to setup page
            return redirect()->route('setup.index');
        }

        // ✅ If database exists, continue as normal
        $user = \Auth::check() ? \Auth::user() : null;

        return view('auth.login', ['loggedInUser' => $user]);
    }

//    public function login(Request $request)
//    {
//        // Step 1: Validate the input
//        $validator = Validator::make($request->all(), [
//            'email' => 'required|email',
//            'password' => 'required|string',
//        ]);
//
//        if ($validator->fails()) {
//            return redirect()->back()
//                ->withErrors($validator)
//                ->withInput();
//        }
//
//        // Step 2: Check if user exists
//        $user = User::where('email', $request->email)->first();
//
//        // Step 3: Check if blocked
//        if ($user && $user->is_blocked == 1) {
//            return redirect()->back()
//                ->withErrors(['email' => 'This user is blocked.'])
//                ->withInput();
//        }
//
//        // Step 4: Attempt login
//        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
//            // Login success
//            return redirect()->intended('dashboard'); // or any other route
//        }
//
//        // Step 5: Login failed (wrong email or password)
//        return redirect()->back()
//            ->withErrors(['email' => 'The provided credentials do not match our records.'])
//            ->withInput();
//
//    }
    public function directLogin(Request $request)
    {
        // simple validation
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            // if you want JSON for POS or simple message:
            if ($request->wantsJson()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }
            // otherwise show error message (or redirect back)
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // regenerate session to prevent fixation
            $request->session()->regenerate();

            // optional: set a flash or anything you need
            // return redirect to dashboard (intended if using intended redirect)
            return $this->login($request);
        }

        // invalid credentials
        if ($request->wantsJson()) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
