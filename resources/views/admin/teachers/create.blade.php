@extends('layouts.app')

@section('title', 'Add teacher')

@section('content')
<header class="mb-8">
    <h1 class="page-title">Add teacher</h1>
    <p class="page-subtitle">Create a teacher account and optionally grant feature permissions.</p>
</header>

<form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-6">
    @csrf
    @include('admin.teachers._form', ['teacher' => null, 'assignedKeys' => old('permissions', [])])
    <button type="submit" class="btn-primary">Create teacher</button>
</form>
@endsection
