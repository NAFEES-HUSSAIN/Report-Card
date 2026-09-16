@extends('layouts.app')

@section('title', 'Input Grades & Performance')

@section('content')
@php
    $isEdit = filled(data_get($student ?? null, 'id'));
    $studentKey = data_get($student ?? null, 'id', $student ?? null);
    $action = $isEdit && Route::has('teacher.form.update')
        ? route('teacher.form.update', $studentKey)
        : (Route::has('teacher.form.store') ? route('teacher.form.store') : url()->current());

    $subjectRows = old('subjects');
    if ($subjectRows === null) {
        $existing = collect(data_get($student ?? null, 'subjects', []));
        if ($existing->isNotEmpty()) {
            $subjectRows = $existing->map(fn ($row) => [
                'subject_id' => data_get($row, 'subject_id'),
                'marks' => data_get($row, 'marks'),
                'remarks' => data_get($row, 'remarks'),
            ])->all();
        } else {
            $subjectRows = [['subject_id' => '', 'marks' => '', 'remarks' => '']];
        }
    }
    if (empty($subjectRows)) {
        $subjectRows = [['subject_id' => '', 'marks' => '', 'remarks' => '']];
    }
@endphp

<header class="mb-8">
    <h1 class="page-title">Input Grades &amp; Performance</h1>
    <p class="page-subtitle">
        {{ $isEdit ? 'Update student marks, remarks, and attendance.' : 'Record student identity, subject marks, and attendance.' }}
    </p>
</header>

<form method="POST" action="{{ $action }}" class="space-y-6" id="grades-form">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <section class="card" aria-labelledby="identity-heading">
        <h2 id="identity-heading" class="mb-5 font-display text-lg font-semibold">Student identity</h2>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="index_number" class="input-label">Index number</label>
                <input type="text" name="index_number" id="index_number" value="{{ old('index_number', data_get($student ?? null, 'index_number')) }}" class="input-field" required autocomplete="off">
                @error('index_number')<p class="field-error" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="name" class="input-label">Full name</label>
                <input type="text" name="name" id="name" value="{{ old('name', data_get($student ?? null, 'name')) }}" class="input-field" required autocomplete="name">
                @error('name')<p class="field-error" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="class_name" class="input-label">Class</label>
                <input
                    type="text"
                    name="class_name"
                    id="class_name"
                    value="{{ old('class_name', $selectedClassName ?? data_get($student ?? null, 'class_name')) }}"
                    class="input-field"
                    placeholder="e.g. Form 3A"
                    required
                    autocomplete="off"
                >
                @error('class_name')<p class="field-error" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="term" class="input-label">Term</label>
                <select name="term" id="term" class="input-field" required>
                    <option value="">Select term</option>
                    @foreach ($termOptions ?? [] as $termName)
                        <option value="{{ $termName }}" @selected((string) old('term', $selectedTerm ?? '') === (string) $termName)>
                            {{ $termName }}
                        </option>
                    @endforeach
                </select>
                @error('term')<p class="field-error" role="alert">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>

    <section class="card" aria-labelledby="subjects-heading">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <h2 id="subjects-heading" class="font-display text-lg font-semibold">Subject marks</h2>
            <button type="button" id="add-subject-row" class="btn-secondary !py-2">Add subject</button>
        </div>
        @error('subjects')<p class="field-error mb-4" role="alert">{{ $message }}</p>@enderror

        <div class="overflow-x-auto">
            <table class="w-full min-w-[40rem] border-collapse" id="subjects-table">
                <thead>
                    <tr>
                        <th scope="col" class="table-th">Subject</th>
                        <th scope="col" class="table-th w-32">Marks</th>
                        <th scope="col" class="table-th">Remarks</th>
                        <th scope="col" class="table-th">Actions</th>
                    </tr>
                </thead>
                <tbody id="subjects-tbody">
                    @foreach ($subjectRows as $index => $row)
                        <tr class="subject-row" data-index="{{ $index }}">
                            <td class="table-td">
                                <label for="subjects_{{ $index }}_subject_id" class="sr-only">Subject</label>
                                <select name="subjects[{{ $index }}][subject_id]" id="subjects_{{ $index }}_subject_id" class="input-field" required>
                                    <option value="">Select subject</option>
                                    @foreach ($availableSubjects ?? [] as $subject)
                                        <option value="{{ $subject->id }}" @selected((string) old("subjects.$index.subject_id", data_get($row, 'subject_id')) === (string) $subject->id)>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("subjects.$index.subject_id")<p class="field-error" role="alert">{{ $message }}</p>@enderror
                            </td>
                            <td class="table-td">
                                <label for="subjects_{{ $index }}_marks" class="sr-only">Marks</label>
                                <input type="number" name="subjects[{{ $index }}][marks]" id="subjects_{{ $index }}_marks" value="{{ old("subjects.$index.marks", data_get($row, 'marks')) }}" class="input-field" min="0" max="100" step="0.1" required>
                                @error("subjects.$index.marks")<p class="field-error" role="alert">{{ $message }}</p>@enderror
                            </td>
                            <td class="table-td">
                                <label for="subjects_{{ $index }}_remarks" class="sr-only">Remarks</label>
                                <input type="text" name="subjects[{{ $index }}][remarks]" id="subjects_{{ $index }}_remarks" value="{{ old("subjects.$index.remarks", data_get($row, 'remarks')) }}" class="input-field">
                                @error("subjects.$index.remarks")<p class="field-error" role="alert">{{ $message }}</p>@enderror
                            </td>
                            <td class="table-td">
                                <div class="table-actions">
                                    <button type="button" class="table-action-delete remove-subject-row" aria-label="Remove subject row">
                                        <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        <span>Remove</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="card" aria-labelledby="teacher-remark-heading">
        <h2 id="teacher-remark-heading" class="mb-2 font-display text-lg font-semibold">Teacher remark</h2>
        <p class="mb-5 text-sm text-[var(--gs-muted)]">Optional overall comment for this report card. Students can see it on their dashboard and full report.</p>
        <div>
            <label for="teacher_remark" class="input-label">Remark (optional)</label>
            <textarea
                name="teacher_remark"
                id="teacher_remark"
                rows="3"
                maxlength="1000"
                class="input-field min-h-[6rem] resize-y"
                placeholder="e.g. Excellent progress this term. Keep up the good work."
            >{{ old('teacher_remark', data_get($student ?? null, 'teacher_remark')) }}</textarea>
            @error('teacher_remark')<p class="field-error" role="alert">{{ $message }}</p>@enderror
        </div>
    </section>

    <section class="card" aria-labelledby="attendance-heading">
        <h2 id="attendance-heading" class="mb-5 font-display text-lg font-semibold">Attendance</h2>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div>
                <label for="days_present" class="input-label">Days present</label>
                <input type="number" name="days_present" id="days_present" value="{{ old('days_present', data_get($student ?? null, 'days_present')) }}" class="input-field" min="0" required>
                @error('days_present')<p class="field-error" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="days_absent" class="input-label">Days absent</label>
                <input type="number" name="days_absent" id="days_absent" value="{{ old('days_absent', data_get($student ?? null, 'days_absent')) }}" class="input-field" min="0" required>
                @error('days_absent')<p class="field-error" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="total_days" class="input-label">Total days</label>
                <input type="number" name="total_days" id="total_days" value="{{ old('total_days', data_get($student ?? null, 'total_days')) }}" class="input-field" min="0" required>
                @error('total_days')<p class="field-error" role="alert">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="btn-primary">{{ $isEdit ? 'Update record' : 'Save grades' }}</button>
        @if (Route::has('teacher.ledger'))
            <a href="{{ route('teacher.ledger') }}" class="btn-secondary">View ledger</a>
        @endif
    </div>
</form>

@php
    $subjectOptions = collect($availableSubjects ?? [])->map(fn ($subject) => [
        'id' => $subject->id,
        'name' => $subject->name,
    ])->values();
@endphp

<template id="subject-row-template">
    <tr class="subject-row">
        <td class="table-td">
            <label class="sr-only subject-id-label">Subject</label>
            <select class="input-field subject-id" required>
                <option value="">Select subject</option>
            </select>
        </td>
        <td class="table-td">
            <label class="sr-only subject-marks-label">Marks</label>
            <input type="number" class="input-field subject-marks" min="0" max="100" step="0.1" required>
        </td>
        <td class="table-td">
            <label class="sr-only subject-remarks-label">Remarks</label>
            <input type="text" class="input-field subject-remarks">
        </td>
        <td class="table-td">
            <div class="table-actions">
                <button type="button" class="table-action-delete remove-subject-row" aria-label="Remove subject row">
                    <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Remove</span>
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
    (() => {
        const tbody = document.getElementById('subjects-tbody');
        const template = document.getElementById('subject-row-template');
        const addBtn = document.getElementById('add-subject-row');
        const subjectOptions = @json($subjectOptions);
        if (!tbody || !template || !addBtn) return;

        const nextIndex = () => {
            let max = -1;
            tbody.querySelectorAll('.subject-row').forEach((row) => {
                const idx = Number(row.dataset.index ?? -1);
                if (idx > max) max = idx;
            });
            return max + 1;
        };

        const fillSubjectSelect = (select) => {
            subjectOptions.forEach((subject) => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                select.appendChild(option);
            });
        };

        const wireRow = (row, index) => {
            row.dataset.index = String(index);
            const subject = row.querySelector('.subject-id');
            const marks = row.querySelector('.subject-marks');
            const remarks = row.querySelector('.subject-remarks');
            if (subject) {
                if (subject.options.length <= 1) fillSubjectSelect(subject);
                subject.name = `subjects[${index}][subject_id]`;
                subject.id = `subjects_${index}_subject_id`;
                row.querySelector('.subject-id-label')?.setAttribute('for', subject.id);
            }
            if (marks) {
                marks.name = `subjects[${index}][marks]`;
                marks.id = `subjects_${index}_marks`;
                row.querySelector('.subject-marks-label')?.setAttribute('for', marks.id);
            }
            if (remarks) {
                remarks.name = `subjects[${index}][remarks]`;
                remarks.id = `subjects_${index}_remarks`;
                row.querySelector('.subject-remarks-label')?.setAttribute('for', remarks.id);
            }
        };

        addBtn.addEventListener('click', () => {
            const fragment = template.content.cloneNode(true);
            wireRow(fragment.querySelector('.subject-row'), nextIndex());
            tbody.appendChild(fragment);
        });

        tbody.addEventListener('click', (event) => {
            const btn = event.target.closest('.remove-subject-row');
            if (!btn || tbody.querySelectorAll('.subject-row').length <= 1) return;
            btn.closest('.subject-row')?.remove();
        });
    })();
</script>
@endsection
