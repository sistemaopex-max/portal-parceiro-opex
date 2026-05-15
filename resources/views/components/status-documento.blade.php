@props(['status'])

@php
    $st = $status instanceof \App\Enums\StatusDocumento
        ? $status
        : \App\Enums\StatusDocumento::from((string) $status);

    [$cor, $iconPath] = match ($st) {
        \App\Enums\StatusDocumento::FaltandoDocumento => [
            'bg-gray-100 text-gray-700 ring-gray-200',
            'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z',
        ],
        \App\Enums\StatusDocumento::Pendente => [
            'bg-amber-100 text-amber-800 ring-amber-200',
            'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
        \App\Enums\StatusDocumento::Valido => [
            'bg-green-100 text-green-800 ring-green-200',
            'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
        \App\Enums\StatusDocumento::Invalido => [
            'bg-red-100 text-red-800 ring-red-200',
            'M9.75 9.75l4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset {$cor}"]) }}>
    <svg class="h-3 w-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
    </svg>
    {{ $st->label() }}
</span>
