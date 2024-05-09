<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
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
            'project_id' => ['nullable'],
            'start_date'  => ['required'],
            'due_date'  => ['nullable'],
            'description'  => ['nullable'],
            'priority'  => ['required'],
            'status'  => ['required'],
            // 'voice_memo'  => ['required','mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'],
            'task_members' => ['required','array'],
            'task_observers' => ['required','array'],
            'document.*' => ['nullable','max:51200'],
            'assigned_to' => 'required',
        ];
    }
}
