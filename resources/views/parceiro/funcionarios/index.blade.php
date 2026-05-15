<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Funcionários">
            <x-slot name="actions">
                <x-config-action-btn :href="route('parceiro.funcionarios.create')" variant="primary">Novo funcionário</x-config-action-btn>
            </x-slot>
        </x-page-heading>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <x-alert>{{ session('status') }}</x-alert>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            @if ($funcionarios->isEmpty())
                <x-empty-state
                    title="Nenhum funcionário cadastrado"
                    description="Adicione funcionários para enviar os documentos exigidos."
                >
                    <x-slot name="actions">
                        <x-config-action-btn :href="route('parceiro.funcionarios.create')" variant="primary">Cadastrar funcionário</x-config-action-btn>
                    </x-slot>
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Nasc.</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">CPF</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Função</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Docs em dia</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($funcionarios as $f)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $f->nome }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $f->data_nascimento?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-6 py-4 font-mono text-gray-600">
                                        {{ $f->cpf ? preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $f->cpf) : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $f->funcao?->nome ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($f->documentacao_em_dia)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Sim</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Não</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <x-config-action-btn :href="route('parceiro.funcionarios.docs.index', $f)" variant="primary">Documentos</x-config-action-btn>
                                            <x-config-action-btn :href="route('parceiro.funcionarios.edit', $f)">Editar</x-config-action-btn>
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
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $funcionarios->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
