<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-black text-marino leading-tight">
            Parceiros
        </h2>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        @php
            $hasFilters = filled(request('busca')) || filled(request('categoria'));
        @endphp

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form
                    method="GET"
                    action="{{ route('admin.parceiros.index') }}"
                    class="mb-6 rounded-md border border-gray-200 bg-gray-50 px-3 py-2.5"
                >
                    <div class="flex min-w-0 flex-nowrap items-center gap-3 overflow-x-auto pb-0.5">
                        <label for="busca" class="shrink-0 text-sm font-medium whitespace-nowrap text-gray-700">Pesquisar</label>
                        <input
                            id="busca"
                            name="busca"
                            type="search"
                            value="{{ request('busca') }}"
                            placeholder="Razão social, CNPJ, cidade…"
                            autocomplete="off"
                            class="h-10 min-w-[10rem] flex-1 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        />
                        <label for="categoria" class="shrink-0 text-sm font-medium whitespace-nowrap text-gray-700">Categoria</label>
                        <select
                            id="categoria"
                            name="categoria"
                            class="h-10 w-44 min-w-[11rem] shrink-0 rounded-md border border-gray-300 bg-white px-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        >
                            <option value="">Todas as categorias</option>
                            @foreach ($filterCategories as $cat)
                                <option value="{{ $cat->id }}" @selected((string) request('categoria') === (string) $cat->id)>
                                    {{ $cat->nome }}
                                </option>
                            @endforeach
                        </select>
                        <button
                            type="submit"
                            class="inline-flex h-10 shrink-0 items-center justify-center rounded-md bg-marino px-4 text-sm font-medium text-white shadow-sm transition hover:bg-marino-dark focus:outline-none focus:ring-2 focus:ring-marino focus:ring-offset-1"
                        >
                            Filtrar
                        </button>
                        @if ($hasFilters)
                            <a
                                href="{{ route('admin.parceiros.index') }}"
                                class="inline-flex h-10 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                            >
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>

                @if ($partners->total() === 0)
                    <p class="mt-6 text-gray-600">
                        {{ $hasFilters ? 'Nenhum parceiro encontrado com os filtros aplicados.' : 'Nenhum parceiro cadastrado ainda.' }}
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="py-2 pe-4 font-medium">Razão Social</th>
                                    <th class="py-2 pe-4 font-medium">CNPJ</th>
                                    <th class="py-2 pe-4 font-medium">Cidade</th>
                                    <th class="py-2 pe-4 font-medium">UF</th>
                                    <th class="py-2 pe-4 font-medium">Ativo</th>
                                    <th class="py-2 pe-4 font-medium">Documentação</th>
                                    <th class="py-2 pe-4 font-medium">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($partners as $partner)
                                    <tr class="align-middle">
                                        <td class="py-2.5 pe-4 font-medium text-gray-900">{{ $partner->razao_social }}</td>
                                        <td class="py-2.5 pe-4 font-mono text-gray-600">{{ $partner->cnpj_formatado ?? '—' }}</td>
                                        <td class="py-2.5 pe-4 text-gray-600">{{ $partner->cidade ?: '—' }}</td>
                                        <td class="py-2.5 pe-4 font-mono text-gray-600">{{ $partner->uf ?: '—' }}</td>
                                        <td class="py-2.5 pe-4">
                                            @if ($partner->ativo)
                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Sim</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Não</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 pe-4">
                                            @if ($partner->documentacaoEmDia())
                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Em dia</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Faltando</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 pe-4">
                                            <div class="flex items-center gap-2">
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
                    <div class="mt-4">
                        {{ $partners->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
