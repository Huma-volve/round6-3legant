<?php

namespace App\Http\Controllers\Api\V1\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

   public function store(UserRequest $request)
{
    $data = $request->validated();
    $data['password'] = Hash::make($data['password']); 

    if (!empty($data['is_verified']) && $data['is_verified'] == true) {
        $data['email_verified_at'] = now();
    } else {
        $data['email_verified_at'] = null;
        $data['is_verified'] = false; 
    }

    $user = User::create($data);

    return response()->json($user, 201);
}

public function update(UserRequest $request, $id)
{
    $user = User::findOrFail($id);
    $data = $request->validated();

    if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']); 
    }

    if (!empty($data['is_verified']) && $data['is_verified'] == true) {
        $data['email_verified_at'] = now();
    } else {
        $data['email_verified_at'] = null;
        $data['is_verified'] = false; 
    }

    $user->update($data);

    return response()->json($user);
}


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
