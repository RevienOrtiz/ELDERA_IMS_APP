<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\LoginCode;
use App\Notifications\LoginCodeNotification;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Try to authenticate using email as username
        $credentials = [
            'email' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Generate 6-digit code and send email
            $loginCode = LoginCode::generateCode($user->email);
            
            try {
                $user->notify(new LoginCodeNotification($loginCode->code));
                Auth::logout(); // Log out until code is verified
                return redirect()->route('verify.code')
                    ->with('message', 'A 6-digit verification code has been sent to your email.');
            } catch (\Exception $e) {
                // If email fails, redirect back to login with error
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Failed to send verification code. Please try again or contact support.']);
            }
        }

        // If email doesn't work, try using username field if it exists
        $user = User::where('email', $request->username)->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            // Generate 6-digit code and send email
            $loginCode = LoginCode::generateCode($user->email);
            
            try {
                $user->notify(new LoginCodeNotification($loginCode->code));
                return redirect()->route('verify.code')
                    ->with('message', 'A 6-digit verification code has been sent to your email.');
            } catch (\Exception $e) {
                // If email fails, redirect back to login with error
                return redirect()->route('login')
                    ->withErrors(['email' => 'Failed to send verification code. Please try again or contact support.']);
            }
        }

        return redirect()->back()
            ->withErrors(['username' => 'Invalid credentials. Please check your username and password.'])
            ->withInput();
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    public function showSignup()
    {
        return view('login.signup');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function showVerifyCode()
    {
        return view('login.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find the login code
        $loginCode = LoginCode::where('code', $request->code)
            ->where('used', false)
            ->first();

        if (!$loginCode || $loginCode->isExpired()) {
            return redirect()->back()
                ->withErrors(['code' => 'Invalid or expired verification code.'])
                ->withInput();
        }

        // Find the user
        $user = User::where('email', $loginCode->email)->first();
        if (!$user) {
            return redirect()->back()
                ->withErrors(['code' => 'User not found.'])
                ->withInput();
        }

        // Mark code as used
        $loginCode->update(['used' => true]);

        // Log in the user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login successful!');
    }

    public function resendCode(Request $request)
    {
        $email = $request->input('email');
        if (!$email) {
            return redirect()->back()
                ->withErrors(['email' => 'Email is required.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->back()
                ->withErrors(['email' => 'User not found.']);
        }

        // Generate new code
        $loginCode = LoginCode::generateCode($user->email);
        
        try {
            $user->notify(new LoginCodeNotification($loginCode->code));
            return redirect()->back()
                ->with('message', 'A new verification code has been sent to your email.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['email' => 'Failed to send verification code. Please try again or contact support.']);
        }
    }

}
