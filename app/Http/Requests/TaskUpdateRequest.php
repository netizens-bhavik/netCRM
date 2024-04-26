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
            'project_id' => ['required'],
            'start_date'  => ['required'],
            'due_date'  => ['required'],
            'description'  => ['required'],
            'priority'  => ['required'],
            'status'  => ['required'],
            // 'voice_memo'  => ['required','mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'],
            'task_members' => ['required','array'],
            'document' => ['nullable','mimes:jpeg,png,jpg,pdf','max:2048']
        ];
    }
}
