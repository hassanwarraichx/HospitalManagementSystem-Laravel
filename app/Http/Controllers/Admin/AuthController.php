<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Faker\Extension\Helper;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            if ($this->authService->login($request)) {
                $request->session()->regenerate();
                $role = Auth::user()->getRoleNames()->first();

                return match ($role) {
                    'admin' => redirect()->route('admin.dashboard'),
                    'doctor' => redirect()->route('doctor.dashboard'),
                    'patient' => redirect()->route('patient.dashboard'),
                    default => redirect('/')
                };
            }

            return back()->withErrors(['email' => 'Invalid credentials.']);
        } catch (\Throwable $e) {
            Helpers::log_error_to_db($e);
            return back()->withErrors(['email' => 'Something went wrong. Please try again later.']);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');

    }

}
