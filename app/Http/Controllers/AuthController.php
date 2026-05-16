<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // show welcome
    public function showWelcome()
    {
        return view('welcome');
    }

    // show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // handle registration
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'income' => 'nullable|integer|min:0',
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password' => Hash::make($request->password),
            'income' => $request->income ?? 0, // Default to 0 if not provided
        ]);

        // Log user in by saving user_id in session
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_name', $user->name);

        return redirect()->route('home')->with('success', 'Registration successful! Welcome to BudgetApp.');
    }

    // handle login
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($v->fails()) {
            return back()->withErrors($v)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_name', $user->name);

        return redirect()->route('home')->with('success', 'Welcome back, ' . $user->name . '!');
    }

    // handle logout
    public function logout(Request $request)
    {
        $userName = $request->session()->get('user_name');
        $request->session()->forget(['user_id','user_name']);
        
        return redirect()->route('welcome')->with('success', 'Successfully logged out. See you soon!');
    }
}