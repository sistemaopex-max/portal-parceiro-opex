<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar função — {{ $funcao->nome }}
            </h2>
            <a href="{{ route('admin.partner-categories.funcoes.index', $category) }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Voltar às funções</a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        <p class="text-sm text-gray-600">Categoria: <span class="font-medium">{{ $category->nome }}</span></p>

        @include('admin.partner-categories._category-alerts')

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route('admin.partner-categories.funcoes.update', [$category, $funcao]) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="funcao_id" value="{{ $funcao->id }}">

                <div>
                    <x-input-label for="nome" value="Nome da função" />
                    <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full" :value="old('nome', $funcao->nome)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="ativo" value="0">
                    <input id="ativo" type="checkbox" name="ativo" value="1" @checked(old('ativo', $funcao->ativo))>
                    <x-input-label for="ativo" value="Função ativa" class="!mb-0" />
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <x-config-action-btn type="submit" variant="primary">Salvar</x-config-action-btn>
                    <x-config-action-btn :href="route('admin.partner-categories.funcoes.index', $category)" variant="default">
                        Cancelar
                    </x-config-action-btn>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
