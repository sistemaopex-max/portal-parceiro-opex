<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Funcionários</h2>
            <x-config-action-btn :href="route('parceiro.funcionarios.create')" variant="primary">Novo funcionário</x-config-action-btn>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if ($funcionarios->isEmpty())
                <p class="text-gray-500 text-sm">Nenhum funcionário cadastrado.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-600">
                                <th class="py-2 pe-4 font-medium">Nome</th>
                                <th class="py-2 pe-4 font-medium">Nasc.</th>
                                <th class="py-2 pe-4 font-medium">CPF</th>
                                <th class="py-2 pe-4 font-medium">Função</th>
                                <th class="py-2 pe-4 font-medium">Docs em dia</th>
                                <th class="py-2 pe-4 font-medium">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($funcionarios as $f)
                                <tr class="align-middle">
                                    <td class="py-2.5 pe-4 font-medium">{{ $f->nome }}</td>
                                    <td class="py-2.5 pe-4 text-gray-600">{{ $f->data_nascimento?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="py-2.5 pe-4 font-mono text-gray-600">
                                        {{ $f->cpf ? preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $f->cpf) : '—' }}
                                    </td>
                                    <td class="py-2.5 pe-4">{{ $f->funcao?->nome ?? '—' }}</td>
                                    <td class="py-2.5 pe-4">
                                        @if ($f->documentacao_em_dia)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Sim</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Não</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 pe-4">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <x-config-action-btn :href="route('parceiro.funcionarios.documentos.index', $f)" variant="primary">Documentos</x-config-action-btn>
                                            <x-config-action-btn :href="route('parceiro.funcionarios.edit', $f)" variant="primary">Editar</x-config-action-btn>
                                            <form action="{{ route('parceiro.funcionarios.destroy', $f) }}" method="POST" class="inline" onsubmit="return confirm('Remover este funcionário?');">
                                                @csrf @method('DELETE')
                                                <x-config-action-btn type="submit" variant="danger">Excluir</x-config-action-btn>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $funcionarios->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
