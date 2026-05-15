<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Editar documento: {{ $tipo->nome }}"
            :back="route('admin.categorias.funcoes.docs.index', [$category, $funcao])"
            back-label="Documentos"
        />
    </x-slot>

    <div class="max-w-2xl space-y-6">
        <p class="text-sm text-gray-600">
            Função: <span class="font-medium text-gray-900">{{ $funcao->nome }}</span>
            <span class="text-gray-400">·</span>
            Categoria: <span class="font-medium text-gray-900">{{ $category->nome }}</span>
        </p>

        @include('admin.categorias._category-alerts')

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.categorias.funcoes.docs.update', [$category, $funcao, $tipo]) }}">
                @csrf
                @method('PUT')
                @include('admin.categorias._form-tipo-documento-funcionario-edit', compact('category', 'funcao', 'tipo'))
            </form>
        </div>
    </div>
</x-app-layout>
