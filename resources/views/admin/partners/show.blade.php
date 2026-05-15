<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="{{ $partner->razao_social }}"
            :subtitle="implode(' · ', array_filter([$partner->category?->nome, $partner->cnpj_formatado, $partner->cidade ? $partner->cidade . ($partner->uf ? '/' . $partner->uf : '') : null]))"
            :back="route('admin.parceiros.index')"
            back-label="Parceiros"
        >
            <x-slot name="actions">
                <x-config-action-btn :href="route('admin.parceiros.edit', $partner)">Editar parceiro</x-config-action-btn>
            </x-slot>
        </x-page-heading>
    </x-slot>

    <div class="space-y-6">

        {{-- Documentos da empresa --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-900">Documentos da empresa</h3>
                <x-config-action-btn
                    :href="route('admin.parceiros.docs.index', $partner)"
                    variant="primary"
                >
                    Validar documentos
                </x-config-action-btn>
            </div>
            @if ($partner->documentosEmpresa->isEmpty())
                <x-empty-state title="Nenhum documento exigido" description="Não há documentos configurados para esta categoria." />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Documento</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Validade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($partner->documentosEmpresa as $doc)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $doc->tipo?->nome ?? '—' }}</td>
                                    <td class="px-6 py-4"><x-status-documento :status="$doc->status" /></td>
                                    <td class="px-6 py-4 text-gray-600">{{ $doc->validade?->format('d/m/Y') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Funcionários --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-900">Funcionários</h3>
            </div>
            @if ($partner->funcionarios->isEmpty())
                <x-empty-state title="Nenhum funcionário cadastrado" description="O parceiro ainda não adicionou funcionários." />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Função</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Documentação</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($partner->funcionarios as $funcionario)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $funcionario->nome }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $funcionario->funcao?->nome ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($funcionario->documentacao_em_dia)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Em dia</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Faltando</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <x-config-action-btn
                                            :href="route('admin.parceiros.funcionarios.docs.index', [$partner, $funcionario])"
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
</x-app-layout>
