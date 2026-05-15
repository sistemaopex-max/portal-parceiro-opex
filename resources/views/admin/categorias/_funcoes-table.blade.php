@props(['category', 'funcoes'])

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">
        <h3 class="text-base font-semibold text-gray-900">Funções cadastradas</h3>
        <x-config-action-btn
            type="button"
            variant="primary"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'nova-funcao')"
        >
            Nova função
        </x-config-action-btn>
    </div>

    <div class="p-6">
        @if ($funcoes->isEmpty())
            <x-empty-state
                title="Nenhuma função cadastrada"
                description="Adicione funções para esta categoria e configure os documentos exigidos."
            />
        @else
            <div class="overflow-x-auto -mx-6 sm:mx-0">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Ativa</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($funcoes as $funcao)
                            @php
                                $updateUrl = route('admin.categorias.funcoes.update', [$category, $funcao]);
                                $destroyUrl = route('admin.categorias.funcoes.destroy', [$category, $funcao]);
                            @endphp
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $funcao->nome }}</td>
                                <td class="px-6 py-3">
                                    @if ($funcao->ativo)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Sim</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Não</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-1.5">
                                        <x-config-action-btn
                                            :href="route('admin.categorias.funcoes.docs.index', [$category, $funcao])"
                                            variant="primary"
                                        >
                                            Documentos
                                        </x-config-action-btn>

                                        <x-config-action-btn
                                            :href="route('admin.categorias.funcoes.edit', [$category, $funcao])"
                                            variant="primary"
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
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
