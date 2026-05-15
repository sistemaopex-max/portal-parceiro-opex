<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Funções de funcionário — {{ $category->nome }}"
            :back="route('admin.categorias.show', $category)"
            back-label="Categoria"
        />
    </x-slot>

    <div class="max-w-4xl space-y-6">
        @include('admin.categorias._category-alerts')

        @include('admin.categorias._funcoes-table', [
            'category' => $category,
            'funcoes' => $funcoes,
        ])
    </div>
</x-app-layout>
