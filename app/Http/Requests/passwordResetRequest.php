<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class passwordResetRequest extends FormRequest
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
            'currunt_password' => 'required',
            'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation|different:currunt_password',
            'password_confirmation' => 'required|min:6',
        ];
    }
}
