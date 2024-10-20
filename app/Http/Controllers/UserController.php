<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Resources\UserResource;
use App\Requests\StoreUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        return UserResource::make($user);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return UserResource::make($user);
    }
}
