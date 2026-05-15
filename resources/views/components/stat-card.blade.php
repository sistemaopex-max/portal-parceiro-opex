@props(['label', 'value', 'color' => 'blue', 'href' => null, 'sub' => null])

@php
$iconBg = match ($color) {
    'green'  => 'bg-green-100 text-green-600',
    'red'    => 'bg-red-100 text-red-600',
    'yellow' => 'bg-amber-100 text-amber-600',
    'gray'   => 'bg-gray-100 text-gray-500',
    default  => 'bg-blue-100 text-blue-600',
};
$valueColor = match ($color) {
    'green'  => 'text-green-700',
    'red'    => 'text-red-700',
    'yellow' => 'text-amber-700',
    'gray'   => 'text-gray-600',
    default  => 'text-blue-700',
};
$base = 'relative flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition';
$extra = $href ? ' hover:shadow-md hover:border-gray-300 cursor-pointer' : '';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $base.$extra]) }}>
@else
    <div {{ $attributes->merge(['class' => $base]) }}>
@endif
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg {{ $iconBg }}">
            {{ $slot }}
        </div>
        <div class="min-w-0">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $label }}</p>
            <p class="mt-0.5 text-2xl font-bold {{ $valueColor }}">{{ $value }}</p>
            @if ($sub)
                <p class="mt-0.5 text-xs text-gray-400">{{ $sub }}</p>
            @endif
        </div>
@if ($href)
    </a>
@else
    </div>
@endif
