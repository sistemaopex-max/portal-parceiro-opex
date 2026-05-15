@props([
    'variant' => 'default',
    'type' => 'button',
    'href' => null,
])

@php
    $classes = match ($variant) {
        'primary' => 'bg-marino border-transparent text-white hover:bg-marino-dark focus:ring-marino',
        'success' => '!bg-green-600 !border-transparent !text-white hover:!bg-green-500 focus:!ring-green-500',
        'warning' => 'bg-white border-amber-300 text-amber-800 hover:bg-amber-50 focus:ring-amber-400',
        'danger'  => 'bg-red-600 border-transparent text-white hover:bg-red-500 focus:ring-red-500',
        'ghost'   => 'bg-transparent border-transparent text-marino hover:bg-marino/10 focus:ring-marino shadow-none',
        default   => 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-marino',
    };

    $classString = "inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 transition ease-in-out duration-150 {$classes}";
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
