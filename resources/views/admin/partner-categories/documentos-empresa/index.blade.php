<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Documentos da empresa — {{ $category->nome }}
            </h2>
            <a href="{{ route('admin.partner-categories.show', $category) }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Voltar à categoria</a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        @include('admin.partner-categories._category-alerts')

        @include('admin.partner-categories._tipo-documento-table', [
            'tipos' => $tipos,
            'category' => $category,
            'routeBase' => 'admin.partner-categories.documentos-empresa',
            'routeParams' => [$category],
            'modalPrefix' => 'documento-empresa',
        ])
    </div>
</x-app-layout>
