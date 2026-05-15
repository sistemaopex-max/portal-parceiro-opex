<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Editar categoria: {{ $category->nome }}"
            :back="route('admin.categorias.show', $category)"
            back-label="Categoria"
        />
    </x-slot>

    <div class="max-w-2xl space-y-6">
        @include('admin.categorias._category-alerts')

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.categorias.update', $category) }}">
                @csrf
                @method('PUT')
                @include('admin.categorias._form-edit', ['category' => $category])
            </form>
        </div>
    </div>
</x-app-layout>
