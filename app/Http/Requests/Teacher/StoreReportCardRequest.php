<?php

namespace App\Http\Requests\Teacher;

use App\Support\GradeCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportCardRequest extends FormRequest
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
            'index_number' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:100'],
            'term' => ['required', 'string', Rule::in(GradeCatalog::termNames())],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*.subject_id' => ['required', 'integer', 'exists:subjects,id', 'distinct'],
            'subjects.*.marks' => ['required', 'numeric', 'min:0', 'max:100'],
            'subjects.*.remarks' => ['nullable', 'string', 'max:255'],
            'days_present' => ['required', 'integer', 'min:0'],
            'days_absent' => ['required', 'integer', 'min:0'],
            'total_days' => ['required', 'integer', 'min:0', 'gte:'.((int) $this->input('days_present') + (int) $this->input('days_absent'))],
        ];
    }
}
