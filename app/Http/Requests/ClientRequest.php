<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'name' => ['required'],
            'email' => ['required','email'],
            'avtar' => ['required','mimes:png,jpg'],
            'country_id' => ['required','integer'],
            'state_id' => ['required','integer'],
            'city_id' => ['required','integer'],
            // 'zipcode' => ['required','regex:/\b\d{5}\b/'],
            'zipcode' => ['required','integer','digits:6'],
            // 'phone_no' => ['required','regex:/(01)[0-9]{9}/'],
            'phone_no' => ['required','integer','digits:10'],
            'company_name' => ['required'],
            'company_website' => ['required','url'],
            'company_address' => ['required'],
            'company_logo' => ['required','mimes:png,jpg'],
            'tax' => ['required'],
            'gst_vat' => ['required'],
            'office_mobile' => ['required','integer','digits:10'],
            'address' => ['required'],
            'note' => ['required']
        ];
    }
}
