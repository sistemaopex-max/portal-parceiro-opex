<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tipos de documento (funcionário)</h2>
            <a href="{{ route('admin.tipos-documento-funcionario.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Novo</a>
        </div>
    </x-slot>
    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">{{ session('status') }}</div>
        @endif
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if ($tipos->isEmpty())
                <p class="text-gray-600">Nenhum tipo cadastrado.</p>
            @else
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead><tr class="text-left text-gray-600"><th class="py-2">Nome</th><th class="py-2">Ativo</th><th class="py-2 text-end">Ações</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($tipos as $tipo)
                            <tr>
                                <td class="py-3 font-medium">{{ $tipo->nome }}</td>
                                <td class="py-3">{{ $tipo->ativo ? 'Sim' : 'Não' }}</td>
                                <td class="py-3 text-end whitespace-nowrap">
                                    <a href="{{ route('admin.tipos-documento-funcionario.edit', $tipo) }}" class="text-indigo-600 hover:text-indigo-900 me-3">Editar</a>
                                    <form action="{{ route('admin.tipos-documento-funcionario.destroy', $tipo) }}" method="POST" class="inline" onsubmit="return confirm('Excluir?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $tipos->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
