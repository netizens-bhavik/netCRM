<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisteruserRequest extends FormRequest
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
            'avtar' => 'nullable|image',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required',
            'phone_no' => 'nullable',
            'date_of_birth' => 'nullable',
            'gender' => 'nullable|in:female,male',
            'date_of_join' => 'nullable|date',
            'address' => 'nullable',
            'role' => 'required'
        ];
    }
}
