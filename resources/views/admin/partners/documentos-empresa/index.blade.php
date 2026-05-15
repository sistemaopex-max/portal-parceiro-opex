<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Documentos da empresa"
            :subtitle="$partner->razao_social"
            :back="route('admin.parceiros.show', $partner)"
            back-label="Voltar ao parceiro"
        />
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <x-alert>{{ session('status') }}</x-alert>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            @if ($documentos->isEmpty())
                <x-empty-state title="Nenhum documento exigido" description="Não há documentos configurados para esta categoria." />
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
                                                    :href="route('admin.docs.download', $doc)"
                                                    target="_blank"
                                                    variant="ghost"
                                                >
                                                    <svg class="me-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                    Visualizar
                                                </x-config-action-btn>
                                                <x-config-action-btn
                                                    type="button"
                                                    variant="primary"
                                                    x-data=""
                                                    x-on:click="$dispatch('open-modal', 'validar-{{ $doc->uuid }}')"
                                                >
                                                    Validar
                                                </x-config-action-btn>
                                            @else
                                                <span class="text-xs text-gray-400">Aguardando envio</span>
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

    {{-- Modais de validação --}}
    @foreach ($documentos as $doc)
        @if ($doc->arquivo_caminho)
            <x-modal name="validar-{{ $doc->uuid }}" focusable>
                <form method="POST" action="{{ route('admin.docs.validar', $doc) }}" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Validar documento</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            <span class="font-medium text-gray-700">{{ $doc->tipo?->nome ?? '—' }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <a href="{{ route('admin.docs.download', $doc) }}" target="_blank" class="text-sm font-medium text-marino hover:underline">
                            Abrir arquivo para revisão ↗
                        </a>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Decisão</label>
                        <select name="decisao" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-marino focus:ring-marino" required>
                            <option value="valido">Válido</option>
                            <option value="invalido">Inválido</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Validade (se válido)</label>
                        <input type="date" name="validade" value="{{ $doc->validade?->format('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-marino focus:ring-marino">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observações (se inválido)</label>
                        <textarea name="observacoes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-marino focus:ring-marino"
                            placeholder="Descreva o motivo da invalidação…"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                        <x-primary-button>Salvar decisão</x-primary-button>
                    </div>
                </form>
            </x-modal>
        @endif
    @endforeach

</x-app-layout>
