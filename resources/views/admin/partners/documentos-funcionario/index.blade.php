<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Documentos — {{ $partner->razao_social }} / {{ $funcionario->nome }}</h2>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <a href="{{ route('admin.parceiros.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Parceiros</a>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2 pe-4">Documento</th>
                            <th class="py-2 pe-4">Status</th>
                            <th class="py-2 pe-4">Validade</th>
                            <th class="py-2 pe-4">Validação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($documentos as $doc)
                            <tr class="align-top">
                                <td class="py-3 pe-4 font-medium">{{ $doc->tipo?->nome }}</td>
                                <td class="py-3 pe-4"><x-status-documento :status="$doc->status" /></td>
                                <td class="py-3 pe-4">{{ $doc->validade?->format('d/m/Y') ?? '—' }}</td>
                                <td class="py-3 pe-4">
                                    @if ($doc->arquivo_caminho)
                                        <form method="POST" action="{{ route('admin.documentos-funcionario.validar', $doc) }}" class="space-y-2 max-w-xs">
                                            @csrf
                                            <div>
                                                <label class="text-xs text-gray-600">Decisão</label>
                                                <select name="decisao" class="block w-full text-xs border-gray-300 rounded-md" required>
                                                    <option value="valido">Válido</option>
                                                    <option value="invalido">Inválido</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-600">Validade (se válido)</label>
                                                <input type="date" name="validade" class="block w-full text-xs border-gray-300 rounded-md">
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-600">Observações (se inválido)</label>
                                                <textarea name="observacoes" rows="2" class="block w-full text-xs border-gray-300 rounded-md"></textarea>
                                            </div>
                                            <button type="submit" class="text-xs bg-marino text-white px-2 py-1 rounded">Salvar</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-500">Aguardando envio</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
