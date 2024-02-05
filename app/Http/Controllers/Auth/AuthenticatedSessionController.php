<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    public function index()
    {
        $data['pageTitle'] = 'Login';
        $data['pageDescription'] = '';

        return view('auth.login', $data);
    }

    public function loginStore(LoginRequest $request)
    {
        try {
            // Check if the user with the given email exists
            if (!User::where('email', $request->email)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email not registered.',
                    'errors' => [
                        'email' => ['Email not registered.'],
                    ],
                ], 422);
            }

            // Check if the password is correct
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password is incorrect.',
                    'errors' => [
                        'password' => ['Password is incorrect.'],
                    ],
                ], 422);
            }

            $user->last_login_at = now();
            $user->save();
            // Log in the user
            Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'data' => [
                    'user' => Auth::user(),
                ],
            ], 200);

        } catch (\Exception $e) {
            // Log the error or handle it as needed
            // For now, just return a generic error response
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function register()
    {
        $data['pageTitle'] = 'Register';
        $data['pageDescription'] = '';

        return view('auth.register', $data);
    }

    public function registerStore(RegisterRequest $request)
    {
        try {
            // Check if the user with the given email exists
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email already exists.',
                    'errors' => [
                        'email' => ['Email already exists.'],
                    ],
                ], 422);
            }

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'uuid' => Str::uuid(),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole('user');

            // Log in the user
            Auth::login($user);
            $user->last_login_at = now();
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful.',
                'data' => [
                    'user' => Auth::user(),
                ],
            ], 200);

        } catch (\Exception $e) {
            // Log the error or handle it as needed
            // For now, just return a generic error response
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
