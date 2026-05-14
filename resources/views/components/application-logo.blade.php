@props([
    'alt' => null,
])

<img
    src="{{ asset('images/logo.png') }}"
    alt="{{ $alt ?? config('app.name', 'Portal') }}"
    loading="eager"
    decoding="async"
    {{ $attributes->merge(['class' => 'block shrink-0 object-contain object-center']) }}
/>
