@props(['href', 'active' => false])

@php
    $classes = $active
        ? 'bg-white/15 text-white shadow-sm ring-1 ring-inset ring-white/20'
        : 'text-white/90 hover:bg-white/10 hover:text-white';
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40 '.$classes]) }}
>
    {{ $slot }}
</a>
