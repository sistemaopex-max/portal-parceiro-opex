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

        @php
            $currentPartnerId = auth()->user()?->currentPartner()?->id;
        @endphp

        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-card">
            @if ($filiais->isEmpty())
                <x-empty-state title="Nenhuma filial cadastrada" description="Adicione uma filial para alternar entre unidades." />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-100 text-sm">
                        <thead>
                            <tr class="bg-zinc-50/80">
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Razão social</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">CNPJ</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Cidade / UF</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Situação</th>
                                <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach ($filiais as $filial)
                                <tr class="transition hover:bg-zinc-50">
                                    <td class="px-6 py-4 font-semibold text-zinc-900">
                                        {{ $filial->razao_social }}
                                        @if ($filial->id === $currentPartnerId && $filial->ativo)
                                            <span class="ml-2 inline-flex items-center rounded-full bg-marino/10 px-2 py-0.5 text-[10px] font-semibold text-marino">Em uso</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-mono text-zinc-500">{{ $filial->cnpj_formatado ?? '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600">
                                        {{ implode(' / ', array_filter([$filial->cidade, $filial->uf])) ?: '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($filial->ativo)
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-green-200">Ativa</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-semibold text-zinc-600 ring-1 ring-zinc-200">Inativa</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <x-config-action-btn :href="route('parceiro.filiais.edit', $filial)">Editar</x-config-action-btn>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <p class="text-xs text-zinc-500">
            Para alternar entre filiais ativas, use o seletor no menu lateral.
            Ativar ou desativar filiais no sistema é feito apenas pelo administrador.
        </p>
    </div>
</x-app-layout>