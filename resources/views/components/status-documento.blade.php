@props(['status'])

@php
    $st = $status instanceof \App\Enums\StatusDocumento
        ? $status
        : \App\Enums\StatusDocumento::from((string) $status);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset '.$st->cor()]) }}>
    {{ $st->label() }}
</span>
