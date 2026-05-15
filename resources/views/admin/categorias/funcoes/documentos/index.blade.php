<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Documentos de funcionário — {{ $funcao->nome }}"
            :subtitle="'Categoria: ' . $category->nome"
            :back="route('admin.categorias.funcoes.index', $category)"
            back-label="Funções"
        />
    </x-slot>

    <div class="max-w-4xl space-y-6">
        @include('admin.categorias._category-alerts')

        @include('admin.categorias._tipo-documento-table', [
            'tipos' => $tipos,
            'category' => $category,
            'routeBase' => 'admin.categorias.funcoes.docs',
            'routeParams' => [$category, $funcao],
            'modalPrefix' => 'documento-funcionario-'.$funcao->id,
            'editPageRoute' => 'admin.categorias.funcoes.docs.edit',
        ])
    </div>
</x-app-layout>
