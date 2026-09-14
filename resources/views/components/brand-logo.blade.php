@props([
    'size' => 'md',
])

@php
    $logoUrl = \App\Support\BrandAssets::schoolLogoUrl();

    $box = match ($size) {
        'sm' => 'h-8 w-8',
        'sidebar' => 'h-14 w-14',
        'lg' => 'h-20 w-20',
        'xl' => 'h-28 w-28',
        default => 'h-10 w-10',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex {$box} shrink-0 items-center justify-center overflow-hidden rounded-full bg-transparent"]) }}>
    <img
        src="{{ $logoUrl }}"
        alt="School logo"
        class="h-full w-full object-contain"
        decoding="async"
    >
</span>
