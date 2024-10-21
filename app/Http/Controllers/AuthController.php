<?php

namespace App\Http\Controllers;

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
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $data             = $request->validated();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $user             = User::create($data);

        auth()->login($user);

        return redirect('/');
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}
