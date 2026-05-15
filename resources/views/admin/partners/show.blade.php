<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $partner->razao_social }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $partner->category?->nome }}
                    @if ($partner->cnpj_formatado)
                        &bull; {{ $partner->cnpj_formatado }}
                    @endif
                    @if ($partner->cidade || $partner->uf)
                        &bull; {{ implode(' / ', array_filter([$partner->cidade, $partner->uf])) }}
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <x-config-action-btn :href="route('admin.parceiros.index')">← Parceiros</x-config-action-btn>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Documentos da empresa --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Documentos da empresa</h3>
            </div>
            <div class="p-6">
                @if ($partner->documentosEmpresa->isEmpty())
                    <p class="text-sm text-gray-500">Nenhum documento exigido para esta categoria.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="py-2 pe-4 font-medium">Documento</th>
                                    <th class="py-2 pe-4 font-medium">Status</th>
                                    <th class="py-2 pe-4 font-medium">Validade</th>
                                    <th class="py-2 pe-4 font-medium">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($partner->documentosEmpresa as $doc)
                                    <tr class="align-middle">
                                        <td class="py-2.5 pe-4 font-medium text-gray-900">{{ $doc->tipo?->nome ?? '—' }}</td>
                                        <td class="py-2.5 pe-4"><x-status-documento :status="$doc->status" /></td>
                                        <td class="py-2.5 pe-4 text-gray-600">{{ $doc->validade?->format('d/m/Y') ?? '—' }}</td>
                                        <td class="py-2.5 pe-4">
                                            @if ($doc->arquivo_caminho)
                                                <x-config-action-btn
                                                    :href="route('admin.documentos-empresa.download', $doc)"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    variant="primary"
                                                >
                                                    Visualizar
                                                </x-config-action-btn>
                                            @else
                                                <span class="text-xs text-gray-400">Sem arquivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Funcionários --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Funcionários</h3>
            </div>
            <div class="p-6">
                @if ($partner->funcionarios->isEmpty())
                    <p class="text-sm text-gray-500">Nenhum funcionário cadastrado.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="py-2 pe-4 font-medium">Nome</th>
                                    <th class="py-2 pe-4 font-medium">Função</th>
                                    <th class="py-2 pe-4 font-medium">Documentação</th>
                                    <th class="py-2 pe-4 font-medium">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($partner->funcionarios as $funcionario)
                                    <tr class="align-middle">
                                        <td class="py-2.5 pe-4 font-medium text-gray-900">{{ $funcionario->nome }}</td>
                                        <td class="py-2.5 pe-4 text-gray-600">{{ $funcionario->funcao?->nome ?? '—' }}</td>
                                        <td class="py-2.5 pe-4">
                                            @if ($funcionario->documentacao_em_dia)
                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Em dia</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Faltando</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 pe-4">
                                            <x-config-action-btn
                                                :href="route('admin.parceiros.funcionarios.documentos.index', [$partner, $funcionario])"
                                                variant="primary"
                                            >
                                                Visualizar documentos
                                            </x-config-action-btn>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
