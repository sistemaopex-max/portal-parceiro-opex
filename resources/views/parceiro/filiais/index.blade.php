<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Minhas filiais">
            <x-slot name="actions">
                <x-config-action-btn :href="route('parceiro.filiais.create')" variant="primary">Nova filial</x-config-action-btn>
            </x-slot>
        </x-page-heading>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <x-alert>{{ session('status') }}</x-alert>
        @endif

        @php $currentPartnerId = session('current_partner_id'); @endphp

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            @if ($filiais->isEmpty())
                <x-empty-state title="Nenhuma filial cadastrada" description="Adicione uma filial para alternar entre unidades." />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Razão social</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">CNPJ</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Cidade / UF</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($filiais as $filial)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $filial->razao_social }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $filial->cnpj_formatado ?? '—' }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ implode(' / ', array_filter([$filial->cidade, $filial->uf])) ?: '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($filial->id == $currentPartnerId)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Ativa</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Inativa</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <x-config-action-btn :href="route('parceiro.filiais.edit', $filial)">Editar</x-config-action-btn>
                                            @if ($filial->id != $currentPartnerId)
                                                <form method="POST" action="{{ route('parceiro.filiais.switch', $filial) }}" class="inline">
                                                    @csrf
                                                    <x-config-action-btn type="submit" variant="primary">Ativar</x-config-action-btn>
                                                </form>
                                            @endif
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
</x-app-layout>
