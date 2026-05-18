@props(['items' => []])

@if (count($items) > 0)
    <nav aria-label="Breadcrumb" class="hidden min-w-0 items-center gap-2 lg:flex">
        @foreach ($items as $item)
            @if (! $loop->first)
                <svg class="h-3.5 w-3.5 shrink-0 text-zinc-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            @endif

            @if ($item['url'])
                <a
                    href="{{ $item['url'] }}"
                    class="max-w-[12rem] truncate text-xs font-medium text-zinc-500 transition hover:text-marino"
                >
                    {{ $item['label'] }}
                </a>
            @else
                <span class="max-w-[14rem] truncate text-sm font-semibold text-zinc-700" aria-current="page">
                    {{ $item['label'] }}
                </span>
            @endif
        @endforeach
    </nav>
@endif
