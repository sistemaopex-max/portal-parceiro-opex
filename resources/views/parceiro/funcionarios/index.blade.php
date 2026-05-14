<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Funcionários</h2>
            <a href="{{ route('parceiro.funcionarios.create') }}" class="inline-flex items-center px-4 py-2 bg-marino border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-marino-dark">Novo</a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if ($funcionarios->isEmpty())
                <p class="text-gray-600">Nenhum funcionário cadastrado.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-600">
                                <th class="py-2 pe-4">Nome</th>
                                <th class="py-2 pe-4">CPF</th>
                                <th class="py-2 pe-4">Função</th>
                                <th class="py-2 pe-4">Docs em dia</th>
                                <th class="py-2 pe-4 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($funcionarios as $f)
                                <tr>
                                    <td class="py-3 pe-4 font-medium">{{ $f->nome }}</td>
                                    <td class="py-3 pe-4 font-mono">{{ $f->cpf }}</td>
                                    <td class="py-3 pe-4">{{ $f->funcao?->nome ?? '—' }}</td>
                                    <td class="py-3 pe-4">{{ $f->documentacao_em_dia ? 'Sim' : 'Não' }}</td>
                                    <td class="py-3 pe-4 text-end whitespace-nowrap">
                                        <a href="{{ route('parceiro.funcionarios.documentos.index', $f) }}" class="text-indigo-600 hover:text-indigo-900 me-2">Documentos</a>
                                        <a href="{{ route('parceiro.funcionarios.edit', $f) }}" class="text-indigo-600 hover:text-indigo-900 me-2">Editar</a>
                                        <form action="{{ route('parceiro.funcionarios.destroy', $f) }}" method="POST" class="inline" onsubmit="return confirm('Remover este funcionário?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Excluir</button>
                                        </form>
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
