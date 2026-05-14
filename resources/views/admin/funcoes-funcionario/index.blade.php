<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Funções de funcionário</h2>
            <a href="{{ route('admin.funcoes-funcionario.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Nova</a>
        </div>
    </x-slot>
    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">{{ session('status') }}</div>
        @endif
        @if ($errors->has('delete'))
            <div class="p-4 bg-red-50 text-red-800 rounded-md text-sm font-medium">{{ $errors->first('delete') }}</div>
        @endif
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if ($funcoes->isEmpty())
                <p class="text-gray-600">Nenhuma função cadastrada.</p>
            @else
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead><tr class="text-left text-gray-600"><th class="py-2">Nome</th><th class="py-2">Ativa</th><th class="py-2 text-end">Ações</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($funcoes as $funcao)
                            <tr>
                                <td class="py-3 font-medium">{{ $funcao->nome }}</td>
                                <td class="py-3">{{ $funcao->ativo ? 'Sim' : 'Não' }}</td>
                                <td class="py-3 text-end whitespace-nowrap">
                                    <a href="{{ route('admin.funcoes-funcionario.edit', $funcao) }}" class="text-indigo-600 hover:text-indigo-900 me-3">Editar</a>
                                    <form action="{{ route('admin.funcoes-funcionario.destroy', $funcao) }}" method="POST" class="inline" onsubmit="return confirm('Excluir?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $funcoes->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
