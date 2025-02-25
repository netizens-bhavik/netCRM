<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
        return [
            'name' => 'required',
            'avtar' => 'nullable|mimes:png,jpg,jpeg',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone_no' => 'nullable',
            'date_of_birth' => 'nullable',
            'gender' => 'nullable',
            'date_of_join' => 'nullable',
            'address' => 'nullable',
            'role' => 'required',
            'adhar_image' => 'nullable|mimes:png,jpg,jpeg'
        ];
    }
}
