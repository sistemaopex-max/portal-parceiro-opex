@props(['href', 'active' => false])

@php
    $classes = $active
        ? 'bg-white/[0.13] text-white font-semibold border-l-2 border-white'
        : 'text-white/70 hover:bg-white/[0.07] hover:text-white border-l-2 border-transparent';
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'group flex items-center gap-3 rounded-lg pl-[10px] pr-3 py-2.5 text-sm transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40 '.$classes]) }}
>
    {{ $slot }}
</a>
