<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Documentos — {{ $funcionario->nome }}"
            :back="route('parceiro.funcionarios.index')"
            back-label="Voltar a funcionários"
        />
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <x-alert>{{ session('status') }}</x-alert>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            @if ($documentos->isEmpty())
                <x-empty-state title="Nenhum documento exigido" description="Ainda não há documentos configurados para esta função." />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Documento</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Validade</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($documentos as $doc)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $doc->tipo?->nome ?? '—' }}</td>
                                    <td class="px-6 py-4"><x-status-documento :status="$doc->status" /></td>
                                    <td class="px-6 py-4 text-gray-600">{{ $doc->validade?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if ($doc->arquivo_caminho)
                                                <x-config-action-btn
                                                    :href="route('parceiro.funcionarios.docs.download', [$funcionario, $doc])"
                                                    target="_blank"
                                                    variant="ghost"
                                                >
                                                    <svg class="me-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                    Visualizar
                                                </x-config-action-btn>
                                            @endif
                                            <x-config-action-btn
                                                type="button"
                                                variant="primary"
                                                x-data=""
                                                x-on:click="$dispatch('open-modal', 'upload-func-{{ $doc->uuid }}')"
                                            >
                                                {{ $doc->arquivo_caminho ? 'Substituir' : 'Enviar' }}
                                            </x-config-action-btn>
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

    {{-- Modais de upload --}}
    @foreach ($documentos as $doc)
        <x-upload-modal
            :modal-name="'upload-func-' . $doc->uuid"
            :action="route('parceiro.funcionarios.docs.upload', [$funcionario, $doc])"
            :doc-nome="$doc->tipo?->nome ?? '—'"
            :validade="$doc->validade"
            :has-file="(bool) $doc->arquivo_caminho"
        />
    @endforeach

</x-app-layout>
