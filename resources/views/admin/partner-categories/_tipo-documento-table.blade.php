@props([
    'tipos',
    'category',
    'routeBase',
    'routeParams' => [],
    'modalPrefix',
    'novoLabel' => 'Novo documento',
])

@php
    $storeUrl = route($routeBase.'.store', $routeParams);
@endphp

<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="text-lg font-semibold text-gray-900">Documentos cadastrados</h3>
        <x-config-action-btn
            type="button"
            variant="primary"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', '{{ $modalPrefix }}-novo')"
        >{{ $novoLabel }}</x-config-action-btn>
    </div>

    @if ($tipos->isEmpty())
        <p class="text-sm text-gray-500">Nenhum documento cadastrado.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">Nome</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">Ativo</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-700">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tipos as $tipo)
                        @php
                            $tipoParams = array_merge($routeParams, [$tipo]);
                            $updateUrl = route($routeBase.'.update', $tipoParams);
                            $destroyUrl = route($routeBase.'.destroy', $tipoParams);
                            $editModalName = $modalPrefix.'-editar-'.$tipo->id;
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $tipo->nome }}</td>
                            <td class="px-4 py-3">{{ $tipo->ativo ? 'Sim' : 'Não' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-config-action-btn
                                        type="button"
                                        variant="primary"
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', '{{ $editModalName }}')"
                                    >
                                        Editar
                                    </x-config-action-btn>

                                    @if ($tipo->ativo)
                                        <form method="POST" action="{{ $updateUrl }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="nome" value="{{ $tipo->nome }}">
                                            <input type="hidden" name="ativo" value="0">
                                            <x-config-action-btn type="submit" variant="danger">Inativar</x-config-action-btn>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ $updateUrl }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="nome" value="{{ $tipo->nome }}">
                                            <input type="hidden" name="ativo" value="1">
                                            <x-config-action-btn type="submit" variant="success">Reativar</x-config-action-btn>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ $destroyUrl }}" class="inline" onsubmit="return confirm('Excluir este documento?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-config-action-btn type="submit" variant="danger">Excluir</x-config-action-btn>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <x-modal :name="$editModalName" :show="$errors->has('nome') && (string) old('tipo_id') === (string) $tipo->id" focusable>
                            <form method="POST" action="{{ $updateUrl }}" class="p-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="tipo_id" value="{{ $tipo->id }}">
                                <input type="hidden" name="ativo" value="{{ $tipo->ativo ? '1' : '0' }}">

                                <h2 class="text-lg font-medium text-gray-900">Editar documento</h2>
                                <p class="mt-1 text-sm text-gray-600">Altere o nome do documento.</p>

                                <div class="mt-4">
                                    <x-input-label for="nome-edit-{{ $tipo->id }}" value="Nome do documento" />
                                    <x-text-input
                                        id="nome-edit-{{ $tipo->id }}"
                                        name="nome"
                                        type="text"
                                        class="block mt-1 w-full"
                                        :value="old('nome', $tipo->nome)"
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
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<x-modal :name="$modalPrefix.'-novo'" :show="$errors->has('nome') && ! old('tipo_id')" focusable>
    <form method="POST" action="{{ $storeUrl }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">{{ $novoLabel }}</h2>
        <p class="mt-1 text-sm text-gray-600">Informe o nome do documento.</p>

        <div class="mt-4">
            <x-input-label for="nome-novo-{{ $modalPrefix }}" value="Nome do documento" />
            <x-text-input
                id="nome-novo-{{ $modalPrefix }}"
                name="nome"
                type="text"
                class="block mt-1 w-full"
                :value="old('nome')"
                placeholder="Ex.: Contrato social"
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
