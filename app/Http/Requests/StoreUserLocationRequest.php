<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !==null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fname' => 'required|string|max:50',
            'lname' => 'required|string|max:50',
            'st_address' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone_no' => 'required|string|max:50',
            'address_type' => 'nullable|in:shipping_address,billing_address,both',
        ];
    }
    public function messages() : array
    {
        return [
            'fname.required' => 'First name is required',
            'lname.required' => 'Last name is required',
            'st_address.required' => 'Street address is required',
            'state.required' => 'State is required',
            'country.required' => 'Country is required',
            'postal_code.required' => 'Postal code is required',
            'phone_no.required' => 'Phone is required',
            'address_type.in' => 'Address type must be shipping_address, billing_address, or both',
        ];
    }
}
