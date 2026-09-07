@props([
    'size' => 'md',
])

@php
    $logoUrl = \App\Models\SiteSetting::logoUrl();

    $box = match ($size) {
        'sm' => 'h-8 w-8 text-xs',
        'lg' => 'h-20 w-20 text-2xl',
        'xl' => 'h-24 w-24 text-3xl',
        default => 'h-10 w-10 text-sm',
    };
@endphp

@if ($logoUrl)
    {{-- Clip to a circle so only the logo shape shows (hides square/black corners). --}}
    <span {{ $attributes->merge(['class' => "inline-flex {$box} shrink-0 items-center justify-center overflow-hidden rounded-full bg-transparent"]) }}>
        <img
            src="{{ $logoUrl }}"
            alt="School logo"
            class="h-full w-full object-contain bg-transparent"
            decoding="async"
        >
    </span>
@else
    <span {{ $attributes->merge(['class' => "inline-flex {$box} shrink-0 items-center justify-center rounded-full brand-gradient font-bold text-white shadow-lg shadow-violet-500/30"]) }}>
        GS
    </span>
@endif
