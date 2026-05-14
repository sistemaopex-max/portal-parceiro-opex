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
                    <div class="flex min-w-0 flex-nowrap items-center gap-3 overflow-x-auto pb-0.5 [-ms-overflow-style:none] [scrollbar-width:thin] [&::-webkit-scrollbar]:h-1.5">
                        <label for="busca" class="shrink-0 text-sm font-medium whitespace-nowrap text-gray-700">Pesquisar</label>
                        <input
                            id="busca"
                            name="busca"
                            type="search"
                            value="{{ request('busca') }}"
                            placeholder="Razão social, e-mail, cidade ou endereço"
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
                                    {{ $cat->name }}
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
                                class="inline-flex h-10 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
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
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="py-2 pe-4">Razão social</th>
                                    <th class="py-2 pe-4 max-w-[14rem]">Endereço</th>
                                    <th class="py-2 pe-4">Cidade</th>
                                    <th class="py-2 pe-4">UF</th>
                                    <th class="py-2 pe-4">Categoria</th>
                                    <th class="py-2 pe-4">Ativo</th>
                                    <th class="py-2 pe-4">E-mail</th>
                                    <th class="py-2 pe-4 text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($partners as $partner)
                                    <tr>
                                        <td class="py-3 pe-4 font-medium text-gray-900">{{ $partner->razao_social }}</td>
                                        <td class="py-3 pe-4 max-w-[14rem] text-gray-700 truncate" title="{{ $partner->endereco }}">{{ $partner->endereco ? \Illuminate\Support\Str::limit($partner->endereco, 48) : '—' }}</td>
                                        <td class="py-3 pe-4 text-gray-700">{{ $partner->cidade ?: '—' }}</td>
                                        <td class="py-3 pe-4 text-gray-700 font-mono">{{ $partner->uf ?: '—' }}</td>
                                        <td class="py-3 pe-4 text-gray-700">{{ $partner->category?->name ?? '—' }}</td>
                                        <td class="py-3 pe-4 text-gray-700">
                                            @if ($partner->ativo)
                                                <span class="text-green-700 font-medium">Sim</span>
                                            @else
                                                <span class="text-gray-500">Não</span>
                                            @endif
                                        </td>
                                        <td class="py-3 pe-4 text-gray-700">{{ $partner->email ?: '—' }}</td>
                                        <td class="py-3 pe-4 text-end whitespace-nowrap">
                                            <a href="{{ route('admin.parceiros.documentos-empresa.index', $partner) }}" class="text-gray-700 hover:text-gray-900 me-3">Docs</a>
                                            <a href="{{ route('admin.parceiros.edit', $partner) }}" class="text-indigo-600 hover:text-indigo-900 me-3">Editar</a>
                                            <form action="{{ route('admin.parceiros.destroy', $partner) }}" method="POST" class="inline" onsubmit="return confirm('Remover este parceiro? O acesso ao portal será excluído.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Excluir</button>
                                            </form>
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
