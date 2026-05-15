@props(['label', 'value', 'color' => 'blue', 'href' => null, 'sub' => null])

@php
$topBorder = match ($color) {
    'green'  => 'border-t-2 border-green-400',
    'red'    => 'border-t-2 border-red-400',
    'yellow' => 'border-t-2 border-amber-400',
    'gray'   => 'border-t-2 border-gray-300',
    default  => 'border-t-2 border-blue-400',
};
$iconBg = match ($color) {
    'green'  => 'bg-green-50 text-green-600',
    'red'    => 'bg-red-50 text-red-600',
    'yellow' => 'bg-amber-50 text-amber-600',
    'gray'   => 'bg-gray-100 text-gray-500',
    default  => 'bg-blue-50 text-blue-600',
};
$valueColor = match ($color) {
    'green'  => 'text-green-700',
    'red'    => 'text-red-700',
    'yellow' => 'text-amber-700',
    'gray'   => 'text-gray-600',
    default  => 'text-blue-700',
};
$base = "relative flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition-shadow {$topBorder}";
$extra = $href ? ' hover:shadow-card-md cursor-pointer' : '';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $base.$extra]) }}>
@else
    <div {{ $attributes->merge(['class' => $base]) }}>
@endif
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl shadow-sm {{ $iconBg }}">
            {{ $slot }}
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-400">{{ $label }}</p>
            <p class="mt-0.5 text-3xl font-black leading-none {{ $valueColor }}">{{ $value }}</p>
            @if ($sub)
                <p class="mt-1 text-xs text-zinc-400">{{ $sub }}</p>
            @endif
        </div>
        @if ($href)
            <svg class="h-4 w-4 shrink-0 text-zinc-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        @endif
@if ($href)
    </a>
@else
    </div>
@endif
