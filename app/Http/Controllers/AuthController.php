<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    //
    public function register(Request $request)
    {


        $verificationCode = rand(100000, 999999);

   $user = User::create([
    'fname' => $request->fname,
    'lname' => $request->lname,
    'username' => $request->username,
    'email' => $request->email,
    'phone' => $request->phone?? null,
    'password' => Hash::make($request->password),
    'role' => 'user', 
    'is_verified' => false,
    'verification_code' => Str::uuid(),
]);
        $token = $user->createToken('auth_token')->plainTextToken;


        Mail::to($user->email)->send(new VerifyEmail($user->verification_code));

        return response()->json([
            'message' => 'User registered. Verification code sent to email.',
            'user' => $user,
            'token' => $token,

        ], 201);
    }

    public function verifyEmail($code)
    {
        $user = User::where('verification_code', $code)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid verification code or user not found.'
            ], 404);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->is_verified = true;
        $user->save();

        return response()->json([
            'message' => 'Email verified successfully!',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if (! $user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (! $user->is_verified) {
            return response()->json(['message' => 'Please verify your email first'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful']);
    }
}
