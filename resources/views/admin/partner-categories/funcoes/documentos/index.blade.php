<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Documentos — {{ $funcao->nome }}
            </h2>
            <a href="{{ route('admin.partner-categories.funcoes.index', $category) }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Voltar às funções</a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        <p class="text-sm text-gray-600">Categoria: <span class="font-medium">{{ $category->name }}</span></p>

        @include('admin.partner-categories._category-alerts')

        @include('admin.partner-categories._tipo-documento-table', [
            'tipos' => $tipos,
            'category' => $category,
            'routeBase' => 'admin.partner-categories.funcoes.documentos',
            'routeParams' => [$category, $funcao],
            'modalPrefix' => 'documento-funcionario-'.$funcao->id,
        ])
    </div>
</x-app-layout>
