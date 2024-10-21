<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Requests\Department\StoreUserRequest;
use App\Resources\UserResource;

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
