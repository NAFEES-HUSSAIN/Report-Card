@props([
    'size' => 'md',
])

@php
    $logoUrl = \App\Support\BrandAssets::schoolLogoUrl();

    // Crest logos need a square box with object-contain — do not clip to a circle.
    $box = match ($size) {
        'sm' => 'h-9 w-9',
        'sidebar' => 'h-14 w-14',
        'lg' => 'h-24 w-24',
        'xl' => 'h-32 w-32',
        'splash' => 'h-40 w-40 sm:h-52 sm:w-52',
        default => 'h-11 w-11',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex {$box} shrink-0 items-center justify-center"]) }}>
    <img
        src="{{ $logoUrl }}"
        alt="School logo"
        class="h-full w-full object-contain"
        decoding="async"
    >
</span>
