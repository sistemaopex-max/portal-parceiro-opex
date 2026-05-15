<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Documentos — {{ $funcao->nome }}
            </h2>
            <a href="{{ route('admin.categorias.funcoes.index', $category) }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Voltar às funções</a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        <p class="text-sm text-gray-600">Categoria: <span class="font-medium">{{ $category->nome }}</span></p>

        @include('admin.categorias._category-alerts')

        @include('admin.categorias._tipo-documento-table', [
            'tipos' => $tipos,
            'category' => $category,
            'routeBase' => 'admin.categorias.funcoes.documentos',
            'routeParams' => [$category, $funcao],
            'modalPrefix' => 'documento-funcionario-'.$funcao->id,
        ])
    </div>
</x-app-layout>
