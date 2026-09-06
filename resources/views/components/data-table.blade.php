@props([
    'title' => null,
    'paginator' => null,
    'minWidth' => '48rem',
])

<section {{ $attributes->merge(['class' => 'card !p-0 overflow-hidden']) }}>
    @if ($title || isset($toolbar))
        <div class="border-b border-[var(--gs-line)] px-6 py-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                @if ($title)
                    <h2 class="font-display text-lg font-semibold">{{ $title }}</h2>
                @endif

                @isset($toolbar)
                    <div class="w-full lg:w-auto">
                        {{ $toolbar }}
                    </div>
                @endisset
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse" style="min-width: {{ $minWidth }}">
            {{ $slot }}
        </table>
    </div>

    @if ($paginator && method_exists($paginator, 'links'))
        <div class="border-t border-[var(--gs-line)] px-6 py-4">
            {{ $paginator->links() }}
        </div>
    @endif
</section>
