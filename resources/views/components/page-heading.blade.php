@props(['title', 'subtitle' => null, 'back' => null, 'backLabel' => 'Voltar'])

<div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0">
        @if ($back)
            <a href="{{ $back }}" class="mb-1 inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-gray-700 transition">
                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                {{ $backLabel }}
            </a>
        @endif
        <h1 class="truncate text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-0.5 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    @if (isset($actions))
        <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
    @endif
</div>
