<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        // Redirect based on role
        if ($user->hasRole('admin')) {
            return redirect()->intended('/dashboard');
        }

        if ($user->hasRole('doctor')) {
            return redirect()->intended('/doctor-queue');
        }

        if ($user->hasRole('receptionist')) {
            return redirect()->intended('/visits');
        }

        // Default fallback
        return redirect()->intended(config('fortify.home'));
    }
}
