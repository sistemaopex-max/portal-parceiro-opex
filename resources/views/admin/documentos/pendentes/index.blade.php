<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Documentos pendentes"
            subtitle="Aguardando validação"
            :back="route('admin.dashboard')"
            back-label="Voltar ao painel"
        />
    </x-slot>

    <div class="space-y-4">
        @if ($parceiros->isEmpty())
            <x-empty-state
                title="Nenhum documento pendente"
                description="Não há documentos aguardando validação no momento."
            />
        @else
            <div class="space-y-4">
                @foreach ($parceiros as $parceiro)
                    @php
                        $docsEmpresa = $parceiro->documentosEmpresa;
                        $funcionariosComPendentes = $parceiro->funcionarios->filter(
                            fn ($f) => $f->documentos->isNotEmpty(),
                        );
                    @endphp

                    <section class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-card">
                        <div class="border-b border-zinc-100 bg-zinc-50/80 px-6 py-4">
                            <h2 class="text-base font-semibold text-zinc-900">{{ $parceiro->razao_social }}</h2>
                            @if ($parceiro->local_filial)
                                <p class="mt-0.5 text-sm text-zinc-500">{{ $parceiro->local_filial }}</p>
                            @endif
                        </div>

                        <div class="divide-y divide-zinc-100 px-6 py-2">
                            @if ($docsEmpresa->isNotEmpty())
                                <div class="py-4">
                                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wider text-zinc-400">
                                        Documentos da empresa
                                    </p>
                                    <ul class="space-y-2">
                                        @foreach ($docsEmpresa as $doc)
                                            <li class="flex flex-wrap items-center justify-between gap-3 rounded-lg px-2 py-2 transition hover:bg-zinc-50">
                                                <div class="min-w-0 flex-1">
                                                    <p class="font-medium text-zinc-900">{{ $doc->tipo?->nome ?? '—' }}</p>
                                                    @if ($doc->validade)
                                                        <p class="mt-0.5 text-xs text-zinc-500">
                                                            Validade informada: {{ $doc->validade->format('d/m/Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="flex shrink-0 items-center gap-2">
                                                    <x-status-documento :status="$doc->status" />
                                                    <x-config-action-btn
                                                        :href="route('admin.parceiros.docs.index', $parceiro) . '?validar=' . $doc->uuid"
                                                        variant="primary"
                                                    >
                                                        Validar
                                                    </x-config-action-btn>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @foreach ($funcionariosComPendentes as $funcionario)
                                <div class="py-4">
                                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-wider text-zinc-400">
                                        Funcionário — {{ $funcionario->nome }}
                                    </p>
                                    <ul class="space-y-2">
                                        @foreach ($funcionario->documentos as $doc)
                                            <li class="flex flex-wrap items-center justify-between gap-3 rounded-lg px-2 py-2 transition hover:bg-zinc-50">
                                                <div class="min-w-0 flex-1">
                                                    <p class="font-medium text-zinc-900">{{ $doc->tipo?->nome ?? '—' }}</p>
                                                    @if ($doc->validade)
                                                        <p class="mt-0.5 text-xs text-zinc-500">
                                                            Validade informada: {{ $doc->validade->format('d/m/Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="flex shrink-0 items-center gap-2">
                                                    <x-status-documento :status="$doc->status" />
                                                    <x-config-action-btn
                                                        :href="route('admin.parceiros.funcionarios.docs.index', [$parceiro, $funcionario]) . '?validar=' . $doc->uuid"
                                                        variant="primary"
                                                    >
                                                        Validar
                                                    </x-config-action-btn>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
