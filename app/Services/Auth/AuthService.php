<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthService
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        return Auth::attempt($credentials);
    }


}
