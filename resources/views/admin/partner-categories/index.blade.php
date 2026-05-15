<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Categorias de parceiro
        </h2>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        @include('admin.partner-categories._category-alerts')

        @include('admin.partner-categories._categories-table', ['categories' => $categories])
    </div>
</x-app-layout>
