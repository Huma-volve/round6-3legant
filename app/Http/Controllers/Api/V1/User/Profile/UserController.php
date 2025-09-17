<?php

namespace App\Http\Controllers\Api\V1\User\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    
        public function updateProfile (UpdateProfileRequest $request)
        {
            try {
                $user = $request->user();

                // update basic information for user
                $user->fill($request->only([
                    'fname',
                    'lname',
                    'username',
                    'email',
                    'phone',
                ]));

                // update password
                if ($request->filled('old_password') && $request->filled('new_password')){
                    if (!Hash::check($request->old_password, $user->password)){
                        return response()->json(['message' => 'Old Password does not match'], 400);
                    }

                    // Validate password confirmation
                    if ($request->filled('new_password_confirmation')) {
                        if ($request->new_password !== $request->new_password_confirmation) {
                            return response()->json(['message' => 'New password and confirmation do not match'], 400);
                        }
                    }

                    $user->password = Hash::make($request->new_password);
                }

                if ($request->hasFile('profile_picture')) {
                    // Delete old picture if exists
                    if ($user->profile_picture && File::exists(public_path($user->profile_picture))) {
                        File::delete(public_path($user->profile_picture));
                    }
                
                    $image = $request->file('profile_picture');
                    $imageName = 'img/users/' . rand(1,10000) . "_" . time() . "." . $image->extension();
                    $image->move(public_path('img/users/'), basename($imageName));
                
                    $user->profile_picture = $imageName; // Save path in DB
                }
                

                $user->save();

                return response()->json([
                    'message' => 'Profile Updated Successfully',
                    'user' => $user,
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'An error occurred while updating profile',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        public function me (Request $request)
        {
            return response()->json($request->user());
        }
}
