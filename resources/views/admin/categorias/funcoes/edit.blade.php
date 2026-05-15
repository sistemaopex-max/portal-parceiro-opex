<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Editar função: {{ $funcao->nome }}"
            :back="route('admin.categorias.funcoes.index', $category)"
            back-label="Funções"
        />
    </x-slot>

    <div class="max-w-2xl space-y-6">
        @include('admin.categorias._category-alerts')

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="mb-6 text-sm text-gray-600">
                Categoria: <span class="font-medium text-gray-900">{{ $category->nome }}</span>
            </p>

            <form method="POST" action="{{ route('admin.categorias.funcoes.update', [$category, $funcao]) }}">
                @csrf
                @method('PUT')
                @include('admin.categorias._form-funcao-edit', ['category' => $category, 'funcao' => $funcao])
            </form>
        </div>
    </div>
</x-app-layout>
