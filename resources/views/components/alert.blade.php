@props(['variant' => 'success'])

@php
[$bg, $border, $text, $iconPath] = match ($variant) {
    'error'   => ['bg-red-50',   'border-red-200',   'text-red-800',   'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z'],
    'warning' => ['bg-amber-50', 'border-amber-200', 'text-amber-800', 'M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H2.645c-1.73 0-2.813-1.874-1.948-3.374L10.051 3.378c.866-1.5 3.032-1.5 3.898 0l8.354 12.748ZM12 15.75h.007v.008H12v-.008Z'],
    'info'    => ['bg-blue-50',  'border-blue-200',  'text-blue-800',  'M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z'],
    default   => ['bg-green-50', 'border-green-200', 'text-green-800', 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
};
@endphp

<div {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-lg border p-4 text-sm {$bg} {$border} {$text}"]) }}>
    <svg class="mt-0.5 h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
    </svg>
    <div class="flex-1 font-medium">{{ $slot }}</div>
</div>
