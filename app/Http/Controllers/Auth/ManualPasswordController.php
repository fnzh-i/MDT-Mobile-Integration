<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService; // Import your Service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ManualPasswordController extends Controller
{
    protected UserService $userService;

    // Inject the UserService just like in your SupervisorController
    public function __construct(UserService $userService) 
    {
        $this->userService = $userService;
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // 1. Verify the token and get the user
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // 2. Use your existing UserService concept
                // We manually save here because UserService->changePassword 
                // usually checks the 'old password', which we don't have.
                $user->password = \Illuminate\Support\Facades\Hash::make($password);
                $user->save();
                
                // If your UserService has a specific 'forceUpdatePassword' method, 
                // you could call it here.
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('secret-login')->with('success', 'Password updated successfully.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}