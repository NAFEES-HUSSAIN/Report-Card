@extends('layouts.app')

@section('title', 'Input Grades & Performance')

@section('content')
@php
    $isEdit = filled(data_get($student ?? null, 'id'));
    $studentKey = data_get($student ?? null, 'id', $student ?? null);
    $action = $isEdit && Route::has('teacher.form.update')
        ? route('teacher.form.update', $studentKey)
        : ($isEdit && Route::has('teacher.form.edit')
            ? route('teacher.form.edit', $studentKey)
            : (Route::has('teacher.form.store') ? route('teacher.form.store') : url()->current()));
    $subjectRows = old('subjects', data_get($student ?? null, 'subjects', [
        ['name' => '', 'marks' => '', 'remarks' => ''],
        ['name' => '', 'marks' => '', 'remarks' => ''],
        ['name' => '', 'marks' => '', 'remarks' => ''],
    ]));
    if (empty($subjectRows)) {
        $subjectRows = [['name' => '', 'marks' => '', 'remarks' => '']];
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
                <input type="text" name="class_name" id="class_name" value="{{ old('class_name', data_get($student ?? null, 'class_name', data_get($student ?? null, 'class'))) }}" class="input-field" required>
                @error('class_name')<p class="field-error" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="term" class="input-label">Term / period</label>
                <input type="text" name="term" id="term" value="{{ old('term', data_get($student ?? null, 'term')) }}" class="input-field" placeholder="e.g. Term 1 2026">
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
                        <th scope="col" class="table-th">Subject name</th>
                        <th scope="col" class="table-th w-32">Marks</th>
                        <th scope="col" class="table-th">Remarks</th>
                        <th scope="col" class="table-th w-24"><span class="sr-only">Remove</span></th>
                    </tr>
                </thead>
                <tbody id="subjects-tbody">
                    @foreach ($subjectRows as $index => $row)
                        <tr class="subject-row" data-index="{{ $index }}">
                            <td class="table-td">
                                <label for="subjects_{{ $index }}_name" class="sr-only">Subject name</label>
                                <input type="text" name="subjects[{{ $index }}][name]" id="subjects_{{ $index }}_name" value="{{ old("subjects.$index.name", data_get($row, 'name')) }}" class="input-field" required>
                                @error("subjects.$index.name")<p class="field-error" role="alert">{{ $message }}</p>@enderror
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
                                <button type="button" class="btn-danger remove-subject-row" aria-label="Remove subject row">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

<template id="subject-row-template">
    <tr class="subject-row">
        <td class="table-td">
            <label class="sr-only subject-name-label">Subject name</label>
            <input type="text" class="input-field subject-name" required>
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
            <button type="button" class="btn-danger remove-subject-row" aria-label="Remove subject row">Remove</button>
        </td>
    </tr>
</template>

<script>
    (() => {
        const tbody = document.getElementById('subjects-tbody');
        const template = document.getElementById('subject-row-template');
        const addBtn = document.getElementById('add-subject-row');
        if (!tbody || !template || !addBtn) return;

        const nextIndex = () => {
            let max = -1;
            tbody.querySelectorAll('.subject-row').forEach((row) => {
                const idx = Number(row.dataset.index ?? -1);
                if (idx > max) max = idx;
            });
            return max + 1;
        };

        const wireRow = (row, index) => {
            row.dataset.index = String(index);
            const name = row.querySelector('.subject-name');
            const marks = row.querySelector('.subject-marks');
            const remarks = row.querySelector('.subject-remarks');
            if (name) { name.name = `subjects[${index}][name]`; name.id = `subjects_${index}_name`; row.querySelector('.subject-name-label')?.setAttribute('for', name.id); }
            if (marks) { marks.name = `subjects[${index}][marks]`; marks.id = `subjects_${index}_marks`; row.querySelector('.subject-marks-label')?.setAttribute('for', marks.id); }
            if (remarks) { remarks.name = `subjects[${index}][remarks]`; remarks.id = `subjects_${index}_remarks`; row.querySelector('.subject-remarks-label')?.setAttribute('for', remarks.id); }
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
