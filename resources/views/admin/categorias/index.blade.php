<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Categorias de parceiro" />
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        @include('admin.categorias._category-alerts')
        @include('admin.categorias._categories-table', ['categories' => $categories])
    </div>
</x-app-layout>
