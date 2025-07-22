<?php

namespace App\Http\Controllers\Admin;

use App\Events\MedicineAlertEvent;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request using AuthService.
     */
    public function login(LoginRequest $request)
    {
        try {
            if ($this->authService->login($request)) {
                $request->session()->regenerate();
                $user=Auth::user();
                $role = Auth::user()->getRoleNames()->first();
                if ($role === 'admin') {
                    event(new MedicineAlertEvent($user));
                }

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

    /**
     * Logout the currently authenticated user.
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
