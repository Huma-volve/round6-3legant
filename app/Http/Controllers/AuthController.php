<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\DB;
use App\Mail\PasswordResetCode;
use Illuminate\Auth\Events\PasswordReset;
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
            'phone' => $request->phone ?? null,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_verified' => false,
            // 'verification_code' => $verificationCode,
            'name' => $request['name'],
            // 'email' => $request['email'],
            // 'password' => Hash::make($request->password),
            // 'is_verified' => false,
            'verification_code' => Str::uuid(),

        ]);
        $token = $user->createToken('auth_token')->plainTextToken;


        // Mail::to($user->email)->send(new VerifyEmail($verificationCode));
        Mail::to($user->email)->send(new VerifyEmail($user->verification_code));

        return response()->json([
            'message' => 'User registered. Verification code sent to email.',
            'user' => $user,
            'token' => $token,
            'varification_code' => $verificationCode,

        ], 201);
    }

   

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required',
        ]);
        // لو يوزر موجود بالايميل 
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User NOT found with this email !!'
            ], 404);
        }
        //  هتشيك كود 
        if ($user->verification_code != $request->verification_code) {
            return response()->json([
                'message' => 'Invalid verification code.'
            ], 400);
        }
        $user->is_verified = true;
        $user->verification_code = null;
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'message' => 'Email verified successfully.',
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



    // ريكويست بالايميل و تشيك موجود 
    // exist-->OTP 
    //ياخد كود ويدخله 
    // تشيك كود انه صح
    // fit--> updatepass with new
    ///
    public function SendResetCode(Request$request)
    {
        $request->validate(['email'=>'required|email']);

        $user=User::where('email',$request->email)->first();
        if(!$user)
        {
            return response()->json(['message'=>'Email Not exist !!'],404);
        }
        $code=rand(100000,999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $code, 'created_at' => now()]
        );
        Mail::to($request->email)->send(new PasswordResetCode($code));

        return response()->json(['message'=>'Reset Code Sent to your Email kindly check']);

    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'email'=>'required|email',
            'code'=>'required',
            'new_password'=>'required|min:6'
        ]);
           $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->first();

              if (!$reset) {
            return response()->json(['message' => 'Invalid code'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful']);
    }
}
