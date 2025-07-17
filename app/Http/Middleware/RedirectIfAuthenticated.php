<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guard = $guards[0] ?? null;

        if (Auth::guard($guard)->check()) {
            $user = Auth::user();
            $role = $user->getRoleNames()->first();

            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'doctor' => redirect()->route('doctor.dashboard'),
                'patient' => redirect()->route('patient.dashboard'),
                default => redirect('/'),
            };
        }

        return $next($request);
    }
}
