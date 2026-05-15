<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Minhas filiais</h2>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-900">Filiais cadastradas</h3>
                <x-config-action-btn :href="route('parceiro.filiais.create')" variant="primary">
                    Nova filial
                </x-config-action-btn>
            </div>

            @php $currentPartnerId = session('current_partner_id'); @endphp

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2 pe-4 font-medium">Razão social</th>
                            <th class="py-2 pe-4 font-medium">CNPJ</th>
                            <th class="py-2 pe-4 font-medium">Cidade / UF</th>
                            <th class="py-2 pe-4 font-medium">Status</th>
                            <th class="py-2 pe-4 font-medium">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($filiais as $filial)
                            <tr class="align-middle">
                                <td class="py-2 pe-4 font-medium">{{ $filial->razao_social }}</td>
                                <td class="py-2 pe-4 text-gray-600">{{ $filial->cnpj_formatado ?? '—' }}</td>
                                <td class="py-2 pe-4 text-gray-600">
                                    @if ($filial->cidade || $filial->uf)
                                        {{ implode(' / ', array_filter([$filial->cidade, $filial->uf])) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="py-2 pe-4">
                                    @if ($filial->id == $currentPartnerId)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Ativa</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Inativa</span>
                                    @endif
                                </td>
                                <td class="py-2 pe-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <x-config-action-btn :href="route('parceiro.filiais.edit', $filial)" variant="primary">
                                            Editar
                                        </x-config-action-btn>
                                        @if ($filial->id != $currentPartnerId)
                                            <form method="POST" action="{{ route('parceiro.filiais.switch', $filial) }}" class="inline">
                                                @csrf
                                                <x-config-action-btn type="submit" variant="indigo">Ativar</x-config-action-btn>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
