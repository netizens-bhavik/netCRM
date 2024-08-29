<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    // Show the form to request a password reset link
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Send a password reset link to the user's email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
    
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        if ($status != Password::RESET_LINK_SENT) {
            $response = [
                'status' => false,
                'message' => __($status),
            ];
            return response()->json($response, 404);
        }
    
        $response = [
            'status' => true,
            'message' => __($status),
        ];
        return response()->json($response, 200);
    }

    // Show the form to reset the password
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->route('token'),
            'email' => $request->old('email', $request->input('email', '')),
            'errors' => session('errors') ? session('errors')->getBag('default') : [],
        ]);
    }

    // Reset the user's password
    public function reset(Request $request)
    {
        // Validate the request data
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Attempt to reset the password
        $response = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();

            event(new PasswordReset($user));
        });

        // Redirect based on the result
        if ($response === Password::PASSWORD_RESET) {
            return redirect()->to('https://staphcrm.com/authentication/login')->with('status', __('Password has been reset.'));
        } else {
            return back()->withErrors(['email' => __($response)]);
        }
    }
}
