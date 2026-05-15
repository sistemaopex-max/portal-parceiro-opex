<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gerenciar categoria: {{ $category->nome }}
            </h2>
            <a href="{{ route('admin.partner-categories.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Voltar à lista</a>
        </div>
    </x-slot>

    <div class="space-y-6 w-fit max-w-full">
        @include('admin.partner-categories._category-alerts')

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden w-fit max-w-full">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">{{ $category->nome }}</h3>
            </div>

            <div class="px-5 py-4">
                <table class="w-auto border-collapse text-base text-gray-800">
                    <colgroup>
                        <col>
                        <col style="width: 40px">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col" class="pb-3 text-left text-base font-semibold text-gray-700 whitespace-nowrap">Documentos da empresa</th>
                            <th aria-hidden="true"></th>
                            <th scope="col" class="pb-3 text-left text-base font-semibold text-gray-700 whitespace-nowrap">Funções de funcionários</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $documentos = $category->tiposDocumentoEmpresa;
                            $funcoes = $category->funcoesFuncionario;
                        @endphp
                        @if ($documentos->isEmpty() && $funcoes->isEmpty())
                            <tr>
                                <td class="py-2 text-gray-500 align-top whitespace-nowrap">Nenhum documento cadastrado.</td>
                                <td aria-hidden="true"></td>
                                <td class="py-2 text-gray-500 align-top whitespace-nowrap">Nenhuma função cadastrada.</td>
                            </tr>
                        @else
                            @for ($i = 0; $i < max($documentos->count(), $funcoes->count()); $i++)
                                <tr>
                                    <td class="py-1.5 align-top leading-relaxed whitespace-nowrap">
                                        @if ($documentos->has($i))
                                            <span class="inline-flex items-baseline gap-2">
                                                <span class="w-2 shrink-0 text-center text-gray-500" aria-hidden="true">•</span>
                                                <span>{{ $documentos[$i]->nome }}</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td aria-hidden="true"></td>
                                    <td class="py-1.5 align-top leading-relaxed whitespace-nowrap">
                                        @if ($funcoes->has($i))
                                            <span class="inline-flex items-baseline gap-2">
                                                <span class="w-2 shrink-0 text-center text-gray-500" aria-hidden="true">•</span>
                                                <span>{{ $funcoes[$i]->nome }}</span>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex flex-wrap gap-2">
                <x-config-action-btn
                    :href="route('admin.partner-categories.documentos-empresa.index', $category)"
                    variant="primary"
                >
                    Gerenciar documentos
                </x-config-action-btn>

                <x-config-action-btn
                    :href="route('admin.partner-categories.funcoes.index', $category)"
                    variant="primary"
                >
                    Gerenciar função de funcionários
                </x-config-action-btn>

                <x-config-action-btn
                    :href="route('admin.partner-categories.edit', $category)"
                    variant="primary"
                >
                    Editar nome
                </x-config-action-btn>

                @if ($category->partners_count === 0)
                    <form
                        action="{{ route('admin.partner-categories.destroy', $category) }}"
                        method="POST"
                        class="inline"
                        onsubmit="return confirm('Excluir esta categoria?');"
                    >
                        @csrf
                        @method('DELETE')
                        <x-config-action-btn type="submit" variant="danger">Excluir categoria</x-config-action-btn>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
