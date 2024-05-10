<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'name' => 'required',
            // 'avtar' => 'required|image',
            // 'avtar' => 'nullable|image',
            'email' => 'required|email|unique:users,email,'.$this->UserId,
            // 'currunt_password' => 'required',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation|different:currunt_password',
            'password_confirmation' => 'min:6',
            'phone_no' => 'nullable',
            'date_of_birth' => 'nullable',
            'gender' => 'nullable',
            'date_of_join' => 'nullable',
            'address' => 'nullable',
            // 'adhar_image' => 'required|image'
            'adhar_image' => 'nullable|image'
        ];
    }
}
