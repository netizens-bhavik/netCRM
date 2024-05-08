<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCreateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'client_id' => ['nullable'],
            'manage_by' => ['nullable'],
            'name' => ['required'],
            'start_date' => ['required','date'],
            'deadline' => ['nullable','date'],
            'summary' => ['nullable'],
            // 'currency' => ['required'],
            'project_members' => ['required','array']
        ];
    }
}
