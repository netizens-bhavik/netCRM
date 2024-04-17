<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
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
            'priority'  => ['required','in:'.implode(',',Task::priority)],
            'status'  => ['required','in:'.implode(',',Task::status)],
            // 'voice_memo'  => ['required','mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav,audio/wav'],
            'voice_memo'  => ['required', 'extensions:wav,audio/wav'],
            'task_members' => ['required','array']
        ];
    }
}
