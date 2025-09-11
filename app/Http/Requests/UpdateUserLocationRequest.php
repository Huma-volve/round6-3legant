<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         // تأكد من أن المستخدم مسجل الدخول
         return $this->user() !== null;
        }
    
        public function rules(): array
        {
            return [
                'fname' => 'sometimes|required|string|max:50',
                'lname' => 'sometimes|required|string|max:50',
                'st_address' => 'sometimes|required|string|max:255',
                'state' => 'required|string|max:100',
                'country' => 'required|in:Egypt,USA,UK,Germany,France',
                'postal_code' => 'sometimes|required|string|max:20',
                'phone_no' => 'sometimes|required|string|max:20',
                'address_type' => 'nullable|in:shipping_address,billing_address,both',
            ];
        }
    }
