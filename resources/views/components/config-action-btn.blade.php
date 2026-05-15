@props([
    'variant' => 'default',
    'type' => 'button',
    'href' => null,
])

@php
    $classes = match ($variant) {
        'primary' => 'bg-marino border-transparent text-white hover:bg-marino-700 focus:ring-marino shadow-sm font-semibold',
        'success' => '!bg-green-600 !border-transparent !text-white hover:!bg-green-500 focus:!ring-green-500 shadow-sm font-semibold',
        'warning' => 'bg-white border-amber-300 text-amber-800 hover:bg-amber-50 focus:ring-amber-400',
        'danger'  => 'bg-red-600 border-transparent text-white hover:bg-red-500 focus:ring-red-500 shadow-sm font-semibold',
        'ghost'   => 'bg-transparent border-transparent text-marino hover:bg-marino/10 focus:ring-marino shadow-none',
        default   => 'bg-white border-zinc-300 text-zinc-700 hover:bg-zinc-50 focus:ring-marino',
    };

    $classString = "inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium rounded-lg border focus:outline-none focus:ring-2 focus:ring-offset-1 transition ease-in-out duration-150 {$classes}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classString]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classString]) }}>
        {{ $slot }}
    </button>
@endif
