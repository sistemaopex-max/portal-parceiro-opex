<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Convites de cadastro">
            <x-slot name="actions">
                <x-config-action-btn :href="route('admin.invitations.create')" variant="primary">
                    Novo convite
                </x-config-action-btn>
            </x-slot>
        </x-page-heading>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <x-alert>{{ session('status') }}</x-alert>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            @if ($invitations->isEmpty())
                <x-empty-state
                    title="Nenhum convite enviado"
                    description="Envie um convite para uma empresa parceira se cadastrar."
                >
                    <x-slot name="actions">
                        <x-config-action-btn :href="route('admin.invitations.create')" variant="primary">Novo convite</x-config-action-btn>
                    </x-slot>
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">E-mail</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Criado em</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Expira em</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($invitations as $invitation)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $invitation->email }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $invitation->category?->nome }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $invitation->criado_em->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $invitation->expira_em->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        @if ($invitation->isUsed())
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Usado</span>
                                        @elseif ($invitation->isExpired())
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Expirado</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('admin.invitations.destroy', $invitation) }}" class="inline" onsubmit="return confirm('Excluir este convite?');">
                                            @csrf @method('DELETE')
                                            <x-config-action-btn type="submit" variant="danger">Excluir</x-config-action-btn>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $invitations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
