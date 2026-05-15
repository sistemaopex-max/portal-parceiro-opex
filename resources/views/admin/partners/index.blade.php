<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Parceiros" />
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <x-alert>{{ session('status') }}</x-alert>
        @endif

        @php $hasFilters = filled(request('busca')) || filled(request('categoria')); @endphp

        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-card">
            {{-- Filtros --}}
            <div class="border-b border-zinc-100 bg-zinc-50/60 px-6 py-4">
                <form method="GET" action="{{ route('admin.parceiros.index') }}"
                    class="flex min-w-0 flex-nowrap items-center gap-3 overflow-x-auto">
                    <label for="busca" class="shrink-0 text-sm font-medium text-zinc-700">Pesquisar</label>
                    <input
                        id="busca" name="busca" type="search" value="{{ request('busca') }}"
                        placeholder="Razão social, CNPJ, cidade…" autocomplete="off"
                        class="h-9 min-w-[10rem] flex-1 rounded-lg border-zinc-300 bg-white px-3 text-sm shadow-sm placeholder:text-zinc-400 focus:border-marino focus:ring-marino"
                    />
                    <label for="categoria" class="shrink-0 text-sm font-medium text-zinc-700">Categoria</label>
                    <select id="categoria" name="categoria"
                        class="h-9 w-44 min-w-[11rem] shrink-0 rounded-lg border-zinc-300 bg-white px-2 text-sm shadow-sm focus:border-marino focus:ring-marino">
                        <option value="">Todas as categorias</option>
                        @foreach ($filterCategories as $cat)
                            <option value="{{ $cat->id }}" @selected((string) request('categoria') === (string) $cat->id)>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="inline-flex h-9 shrink-0 items-center rounded-lg bg-marino px-4 text-sm font-semibold text-white shadow-sm hover:bg-marino-700 focus:outline-none focus:ring-2 focus:ring-marino focus:ring-offset-1 transition">
                        Filtrar
                    </button>
                    @if ($hasFilters)
                        <a href="{{ route('admin.parceiros.index') }}"
                            class="inline-flex h-9 shrink-0 items-center rounded-lg border border-zinc-300 bg-white px-4 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition">
                            Limpar
                        </a>
                    @endif
                </form>
            </div>

            @if ($partners->total() === 0)
                <x-empty-state
                    title="{{ $hasFilters ? 'Nenhum resultado' : 'Nenhum parceiro cadastrado' }}"
                    description="{{ $hasFilters ? 'Tente outros filtros.' : 'Convide um parceiro para começar.' }}"
                >
                    <x-slot name="icon">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                    </x-slot>
                    @if (! $hasFilters)
                        <x-slot name="actions">
                            <x-config-action-btn :href="route('admin.invitations.create')" variant="primary">Convidar parceiro</x-config-action-btn>
                        </x-slot>
                    @endif
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-100 text-sm">
                        <thead>
                            <tr class="bg-zinc-50/80">
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Razão Social</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">CNPJ</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Cidade</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">UF</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Ativo</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Documentação</th>
                                <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach ($partners as $partner)
                                <tr class="transition hover:bg-zinc-50">
                                    <td class="px-6 py-4 font-semibold text-zinc-900">{{ $partner->razao_social }}</td>
                                    <td class="px-6 py-4 font-mono text-zinc-500">{{ $partner->cnpj_formatado ?? '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600">{{ $partner->cidade ?: '—' }}</td>
                                    <td class="px-6 py-4 font-mono text-zinc-500">{{ $partner->uf ?: '—' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($partner->ativo)
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-green-200">Sim</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-semibold text-zinc-500 ring-1 ring-zinc-200">Não</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($partner->documentacaoEmDia())
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-green-200">Em dia</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-600 ring-1 ring-red-200">Faltando</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <x-config-action-btn :href="route('admin.parceiros.show', $partner)" variant="primary">
                                                Visualizar
                                            </x-config-action-btn>
                                            <x-config-action-btn :href="route('admin.parceiros.edit', $partner)">
                                                Editar
                                            </x-config-action-btn>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-zinc-100 px-6 py-4">
                    {{ $partners->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
