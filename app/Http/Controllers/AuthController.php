<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Requests\auth\RegisterRequest;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate()
    {
        $credentials = request()->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect('/');
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function register()
    {
        $departments = Department::all();
        return view('auth.register', compact('departments'));
    }

    public function store(RegisterRequest $request)
    {
        $data                   = $request->validated();
        $data['password']       = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['remember_token'] = bin2hex(random_bytes(32));
        $user                   = User::create($data);

        auth()->login($user);

        return redirect('/')->with('success', 'User registered successfully');
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}
