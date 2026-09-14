@props([
    'user',
    'size' => 'md',
])

@php
    $classes = match ($size) {
        'sm' => 'h-8 w-8 rounded-xl',
        'lg' => 'h-24 w-24 rounded-3xl',
        'xl' => 'h-28 w-28 rounded-3xl',
        default => 'h-10 w-10 rounded-2xl',
    };
@endphp

<img
    src="{{ $user->profilePhotoUrl() }}"
    data-user-avatar="{{ $user->id }}"
    data-original-src="{{ $user->profilePhotoUrl() }}"
    alt="{{ $user->name }} profile photo"
    {{ $attributes->merge(['class' => "{$classes} object-cover ring-2 ring-[var(--gs-line)] bg-[var(--gs-surface)]"]) }}
>
