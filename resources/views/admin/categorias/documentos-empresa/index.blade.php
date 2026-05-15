<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Documentos da empresa — {{ $category->nome }}"
            :back="route('admin.categorias.show', $category)"
            back-label="Categoria"
        />
    </x-slot>

    <div class="max-w-4xl space-y-6">
        @include('admin.categorias._category-alerts')

        @include('admin.categorias._tipo-documento-table', [
            'tipos' => $tipos,
            'category' => $category,
            'routeBase' => 'admin.categorias.docs',
            'routeParams' => [$category],
            'modalPrefix' => 'documento-empresa',
        ])
    </div>
</x-app-layout>
