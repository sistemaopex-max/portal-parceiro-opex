@props([
    'tipos',
    'category',
    'routeBase',
    'routeParams' => [],
    'modalPrefix',
    'novoLabel' => 'Novo documento',
    'showToolbarNovo' => true,
    'editPageRoute' => null,
])

@php
    $storeUrl = route($routeBase.'.store', $routeParams);
@endphp

<div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 bg-white px-6 py-4">
        <h3 class="text-base font-semibold text-zinc-900">Documentos cadastrados</h3>
        @if ($showToolbarNovo)
            <x-config-action-btn
                type="button"
                variant="primary"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', '{{ $modalPrefix }}-novo')"
            >{{ $novoLabel }}</x-config-action-btn>
        @endif
    </div>

    @if ($tipos->isEmpty())
        <x-empty-state title="Nenhum item cadastrado" description="Clique em '{{ $novoLabel }}' para adicionar.">
            @if (! $showToolbarNovo)
                <x-slot name="actions">
                    <x-config-action-btn
                        type="button"
                        variant="primary"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', '{{ $modalPrefix }}-novo')"
                    >{{ $novoLabel }}</x-config-action-btn>
                </x-slot>
            @endif
        </x-empty-state>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-100 text-sm">
                <thead>
                    <tr class="bg-zinc-50/80">
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Nome</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Ativo</th>
                        <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach ($tipos as $tipo)
                        @php
                            $tipoParams = array_merge($routeParams, [$tipo]);
                            $updateUrl = route($routeBase.'.update', $tipoParams);
                            $destroyUrl = route($routeBase.'.destroy', $tipoParams);
                            $editModalName = $modalPrefix.'-editar-'.$tipo->id;
                            $editPageUrl = $editPageRoute ? route($editPageRoute, $tipoParams) : null;
                        @endphp
                        <tr class="transition hover:bg-zinc-50">
                            <td class="px-6 py-3.5 font-semibold text-zinc-900">{{ $tipo->nome }}</td>
                            <td class="px-6 py-3.5">
                                @if ($tipo->ativo)
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-green-200">Sim</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-semibold text-zinc-500 ring-1 ring-zinc-200">Não</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-1.5">
                                    @if ($editPageUrl)
                                        <x-config-action-btn
                                            :href="$editPageUrl"
                                            variant="primary"
                                        >
                                            Editar
                                        </x-config-action-btn>
                                    @else
                                        <x-config-action-btn
                                            type="button"
                                            variant="primary"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', '{{ $editModalName }}')"
                                        >
                                            Editar
                                        </x-config-action-btn>
                                    @endif

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

                        @unless ($editPageRoute)
                        <x-modal :name="$editModalName" :show="$errors->has('nome') && (string) old('tipo_id') === (string) $tipo->id" focusable>
                            <form method="POST" action="{{ $updateUrl }}" class="p-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="tipo_id" value="{{ $tipo->id }}">
                                <input type="hidden" name="ativo" value="{{ $tipo->ativo ? '1' : '0' }}">

                                <h2 class="text-lg font-semibold text-zinc-900">Editar documento</h2>
                                <p class="mt-1 text-sm text-zinc-500">Altere o nome do documento.</p>

                                <div class="mt-5">
                                    <x-input-label for="nome-edit-{{ $tipo->id }}" value="Nome do documento" />
                                    <x-text-input
                                        id="nome-edit-{{ $tipo->id }}"
                                        name="nome"
                                        type="text"
                                        class="mt-1 block w-full"
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
                        @endunless
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<x-modal :name="$modalPrefix.'-novo'" :show="$errors->has('nome') && ! old('tipo_id')" focusable>
    <form method="POST" action="{{ $storeUrl }}" class="p-6">
        @csrf

        <h2 class="text-lg font-semibold text-zinc-900">{{ $novoLabel }}</h2>
        <p class="mt-1 text-sm text-zinc-500">Informe o nome do documento.</p>

        <div class="mt-5">
            <x-input-label for="nome-novo-{{ $modalPrefix }}" value="Nome do documento" />
            <x-text-input
                id="nome-novo-{{ $modalPrefix }}"
                name="nome"
                type="text"
                class="mt-1 block w-full"
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
