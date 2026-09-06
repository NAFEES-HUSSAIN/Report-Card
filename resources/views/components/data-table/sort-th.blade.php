@props([
    'column',
    'label',
    'sort',
    'direction' => 'asc',
])

@php
    $isActive = $sort === $column;
    $arrow = $isActive ? ($direction === 'asc' ? '↑' : '↓') : '';
    $href = \App\Support\TableSort::url(request(), $column, $sort, $direction);
@endphp

<th scope="col" {{ $attributes->merge(['class' => 'table-th']) }}>
    <a href="{{ $href }}" class="inline-flex items-center justify-center gap-1.5 text-[var(--gs-muted)] transition hover:text-[var(--gs-primary)] {{ $isActive ? 'text-[var(--gs-primary)]' : '' }}">
        <span>{{ $label }}</span>
        @if ($arrow)
            <span class="text-[0.7rem] font-bold" aria-hidden="true">{{ $arrow }}</span>
            <span class="sr-only">sorted {{ $direction }}</span>
        @endif
    </a>
</th>
