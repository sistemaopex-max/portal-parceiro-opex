@props(['categories'])

<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="text-lg font-semibold text-gray-900">Categorias cadastradas</h3>
        <x-config-action-btn
            type="button"
            variant="primary"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'nova-categoria')"
        >
            Nova categoria
        </x-config-action-btn>
    </div>

    @if ($categories->isEmpty())
        <p class="text-sm text-gray-500">Nenhuma categoria cadastrada ainda.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">Nome</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">Ativa</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $category->nome }}</td>
                            <td class="px-4 py-3">{{ $category->ativo ? 'Sim' : 'Não' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-config-action-btn
                                        :href="route('admin.partner-categories.show', $category)"
                                        variant="primary"
                                    >
                                        Gerenciar
                                    </x-config-action-btn>

                                    @if ($category->partners_count === 0)
                                        <form
                                            method="POST"
                                            action="{{ route('admin.partner-categories.destroy', $category) }}"
                                            class="inline"
                                            onsubmit="return confirm('Excluir esta categoria?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <x-config-action-btn type="submit" variant="danger">Excluir</x-config-action-btn>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>

<x-modal name="nova-categoria" :show="$errors->has('nome') || $errors->has('descricao')" focusable maxWidth="lg">
    <form method="POST" action="{{ route('admin.partner-categories.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">Nova categoria</h2>
        <p class="mt-1 text-sm text-gray-600">Informe os dados da categoria de parceiro.</p>

        <div class="mt-4 space-y-4">
            <div>
                <x-input-label for="nome-nova-categoria" value="Nome" />
                <x-text-input
                    id="nome-nova-categoria"
                    name="nome"
                    type="text"
                    class="block mt-1 w-full"
                    :value="old('nome')"
                    required
                    autofocus
                />
                <x-input-error class="mt-2" :messages="$errors->get('nome')" />
            </div>

            <div>
                <x-input-label for="descricao-nova-categoria" value="Descrição (opcional)" />
                <textarea
                    id="descricao-nova-categoria"
                    name="descricao"
                    rows="3"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                >{{ old('descricao') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="ativo" value="0">
                <input id="ativo-nova-categoria" type="checkbox" name="ativo" value="1" @checked((string) old('ativo', '1') === '1')>
                <x-input-label for="ativo-nova-categoria" value="Categoria ativa" class="!mb-0" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                Cancelar
            </x-secondary-button>
            <x-primary-button>Confirmar</x-primary-button>
        </div>
    </form>
</x-modal>
