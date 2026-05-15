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

        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-card">
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
                    <table class="min-w-full divide-y divide-zinc-100 text-sm">
                        <thead>
                            <tr class="bg-zinc-50/80">
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Nome</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Nasc.</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">CPF</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Função</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Docs em dia</th>
                                <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach ($funcionarios as $f)
                                <tr class="transition hover:bg-zinc-50">
                                    <td class="px-6 py-4 font-semibold text-zinc-900">{{ $f->nome }}</td>
                                    <td class="px-6 py-4 text-zinc-500">{{ $f->data_nascimento?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="px-6 py-4 font-mono text-zinc-500">
                                        {{ $f->cpf ? preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $f->cpf) : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600">{{ $f->funcao?->nome ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($f->documentacao_em_dia)
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-green-200">Sim</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-600 ring-1 ring-red-200">Não</span>
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
                <div class="border-t border-zinc-100 px-6 py-4">
                    {{ $funcionarios->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
