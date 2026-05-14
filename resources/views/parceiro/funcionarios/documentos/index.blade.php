<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Documentos — {{ $funcionario->nome }}</h2>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <a href="{{ route('parceiro.funcionarios.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Voltar</a>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2 pe-4">Documento</th>
                            <th class="py-2 pe-4">Status</th>
                            <th class="py-2 pe-4">Validade</th>
                            <th class="py-2 pe-4 text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($documentos as $doc)
                            <tr>
                                <td class="py-3 pe-4 font-medium">{{ $doc->tipo?->nome ?? '—' }}</td>
                                <td class="py-3 pe-4"><x-status-documento :status="$doc->status" /></td>
                                <td class="py-3 pe-4">{{ $doc->validade?->format('d/m/Y') ?? '—' }}</td>
                                <td class="py-3 pe-4 text-end whitespace-nowrap">
                                    @if ($doc->arquivo_caminho)
                                        <a href="{{ route('parceiro.funcionarios.documentos.download', [$funcionario, $doc]) }}" class="text-indigo-600 hover:text-indigo-900 me-3">Baixar</a>
                                    @endif
                                    <form class="inline-block ms-2 align-top" method="POST" action="{{ route('parceiro.funcionarios.documentos.upload', [$funcionario, $doc]) }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="arquivo" accept=".pdf,.jpg,.jpeg,.png" class="text-xs max-w-[10rem]" required>
                                        <input type="date" name="validade" class="text-xs border-gray-300 rounded ms-1" required>
                                        <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-900 ms-1">Enviar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
