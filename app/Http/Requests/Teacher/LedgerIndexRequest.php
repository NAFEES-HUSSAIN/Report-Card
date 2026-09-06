<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class LedgerIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isTeacher() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'school_class_id' => ['nullable', 'integer', 'exists:school_classes,id'],
            'term_id' => ['nullable', 'integer', 'exists:terms,id'],
        ];
    }
}
