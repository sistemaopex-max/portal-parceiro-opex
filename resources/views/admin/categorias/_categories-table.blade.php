@props(['categories'])

<div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 bg-white px-6 py-4">
        <h3 class="text-base font-semibold text-zinc-900">Categorias cadastradas</h3>
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
        <x-empty-state title="Nenhuma categoria cadastrada" description="Crie a primeira categoria usando o botão acima." />
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-100 text-sm">
                <thead>
                    <tr class="bg-zinc-50/80">
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Nome</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Ativa</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach ($categories as $category)
                        <tr class="transition hover:bg-zinc-50">
                            <td class="px-6 py-3.5 font-semibold text-zinc-900">{{ $category->nome }}</td>
                            <td class="px-6 py-3.5">
                                @if ($category->ativo)
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-green-200">Sim</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-semibold text-zinc-500 ring-1 ring-zinc-200">Não</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <x-config-action-btn
                                        :href="route('admin.categorias.show', $category)"
                                        variant="primary"
                                    >
                                        Gerenciar
                                    </x-config-action-btn>

                                    @if ($category->partners_count === 0)
                                        <form
                                            method="POST"
                                            action="{{ route('admin.categorias.destroy', $category) }}"
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

        <div class="border-t border-zinc-100 px-6 py-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>

<x-modal name="nova-categoria" :show="$errors->has('nome') || $errors->has('descricao')" focusable maxWidth="lg">
    <form method="POST" action="{{ route('admin.categorias.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-semibold text-zinc-900">Nova categoria</h2>
        <p class="mt-1 text-sm text-zinc-500">Informe os dados da categoria de parceiro.</p>

        <div class="mt-5 space-y-4">
            <div>
                <x-input-label for="nome-nova-categoria" value="Nome" />
                <x-text-input
                    id="nome-nova-categoria"
                    name="nome"
                    type="text"
                    class="mt-1 block w-full"
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
                    class="mt-1 block w-full rounded-lg border-zinc-300 text-sm shadow-sm focus:border-marino focus:ring-marino"
                >{{ old('descricao') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="ativo" value="0">
                <input id="ativo-nova-categoria" type="checkbox" name="ativo" value="1" @checked((string) old('ativo', '1') === '1') class="rounded border-zinc-300 text-marino focus:ring-marino">
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
