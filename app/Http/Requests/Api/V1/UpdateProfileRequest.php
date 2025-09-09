<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id; // current authenticated user
        
        return [
            'fname' => 'sometimes|string|max:255',
            'lname' => 'sometimes|string|max:255',
            'username' => [
                'sometimes','string','max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'email' => [
                'sometimes','email','max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'phone' => 'nullable|string|max:20',
    
            // password fields
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
            'new_password_confirmation' => 'nullable|string|min:6',
    
            // profile picture
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
    
}
