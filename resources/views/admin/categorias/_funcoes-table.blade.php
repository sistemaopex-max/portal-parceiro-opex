@props(['category', 'funcoes'])

<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="text-lg font-semibold text-gray-900">Funções cadastradas</h3>
        <x-config-action-btn
            type="button"
            variant="primary"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'nova-funcao')"
        >
            Nova função
        </x-config-action-btn>
    </div>

    @if ($funcoes->isEmpty())
        <p class="text-sm text-gray-500">Nenhuma função cadastrada para esta categoria.</p>
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
                    @foreach ($funcoes as $funcao)
                        @php
                            $updateUrl = route('admin.categorias.funcoes.update', [$category, $funcao]);
                            $destroyUrl = route('admin.categorias.funcoes.destroy', [$category, $funcao]);
                            $editModalName = 'editar-funcao-'.$funcao->id;
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $funcao->nome }}</td>
                            <td class="px-4 py-3">{{ $funcao->ativo ? 'Sim' : 'Não' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-config-action-btn
                                        :href="route('admin.categorias.funcoes.documentos.index', [$category, $funcao])"
                                        variant="primary"
                                    >
                                        Documentos
                                    </x-config-action-btn>

                                    <x-config-action-btn
                                        type="button"
                                        variant="primary"
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', '{{ $editModalName }}')"
                                    >
                                        Editar
                                    </x-config-action-btn>

                                    @if ($funcao->ativo)
                                        <form method="POST" action="{{ $updateUrl }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="nome" value="{{ $funcao->nome }}">
                                            <input type="hidden" name="ativo" value="0">
                                            <x-config-action-btn type="submit" variant="danger">Inativar</x-config-action-btn>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ $updateUrl }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="nome" value="{{ $funcao->nome }}">
                                            <input type="hidden" name="ativo" value="1">
                                            <x-config-action-btn type="submit" variant="success">Reativar</x-config-action-btn>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ $destroyUrl }}" class="inline" onsubmit="return confirm('Excluir esta função?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-config-action-btn type="submit" variant="danger">Excluir</x-config-action-btn>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <x-modal
                            :name="$editModalName"
                            :show="$errors->has('nome') && (string) old('funcao_id') === (string) $funcao->id"
                            focusable
                        >
                            <form method="POST" action="{{ $updateUrl }}" class="p-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="funcao_id" value="{{ $funcao->id }}">

                                <h2 class="text-lg font-medium text-gray-900">Editar função</h2>
                                <p class="mt-1 text-sm text-gray-600">Altere o nome e o status da função.</p>

                                <div class="mt-4 space-y-4">
                                    <div>
                                        <x-input-label for="nome-edit-funcao-{{ $funcao->id }}" value="Nome da função" />
                                        <x-text-input
                                            id="nome-edit-funcao-{{ $funcao->id }}"
                                            name="nome"
                                            type="text"
                                            class="block mt-1 w-full"
                                            :value="old('nome', $funcao->nome)"
                                            required
                                        />
                                        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <input type="hidden" name="ativo" value="0">
                                        <input
                                            id="ativo-edit-funcao-{{ $funcao->id }}"
                                            type="checkbox"
                                            name="ativo"
                                            value="1"
                                            @checked(old('funcao_id') == $funcao->id ? (bool) old('ativo') : $funcao->ativo)
                                        >
                                        <x-input-label for="ativo-edit-funcao-{{ $funcao->id }}" value="Função ativa" class="!mb-0" />
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
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<x-modal name="nova-funcao" :show="$errors->has('nome') && ! old('funcao_id')" focusable>
    <form method="POST" action="{{ route('admin.categorias.funcoes.store', $category) }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">Nova função</h2>
        <p class="mt-1 text-sm text-gray-600">Informe o nome da função.</p>

        <div class="mt-4">
            <x-input-label for="nome-funcao" value="Nome da função" />
            <x-text-input
                id="nome-funcao"
                name="nome"
                type="text"
                class="block mt-1 w-full"
                :value="old('nome')"
                placeholder="Ex.: Motorista"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                Cancelar
            </x-secondary-button>
            <x-primary-button>Confirmar</x-primary-button>
        </div>
    </form>
</x-modal>
