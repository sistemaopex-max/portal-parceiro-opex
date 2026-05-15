<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Convites de cadastro</h2>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-900">Convites enviados</h3>
                <x-config-action-btn :href="route('admin.invitations.create')" variant="primary">
                    Novo convite
                </x-config-action-btn>
            </div>

            @if ($invitations->isEmpty())
                <p class="text-sm text-gray-500">Nenhum convite enviado ainda.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="text-left text-gray-600">
                                <th class="py-2 pe-4 font-medium">E-mail</th>
                                <th class="py-2 pe-4 font-medium">Nome</th>
                                <th class="py-2 pe-4 font-medium">Categoria</th>
                                <th class="py-2 pe-4 font-medium">Criado em</th>
                                <th class="py-2 pe-4 font-medium">Expira em</th>
                                <th class="py-2 pe-4 font-medium">Status</th>
                                <th class="py-2 pe-4 font-medium">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($invitations as $invitation)
                                <tr class="align-middle">
                                    <td class="py-2 pe-4">{{ $invitation->email }}</td>
                                    <td class="py-2 pe-4 text-gray-600">{{ '—' }}</td>
                                    <td class="py-2 pe-4">{{ $invitation->category?->nome }}</td>
                                    <td class="py-2 pe-4 text-gray-500">{{ $invitation->criado_em->format('d/m/Y H:i') }}</td>
                                    <td class="py-2 pe-4 text-gray-500">{{ $invitation->expira_em->format('d/m/Y H:i') }}</td>
                                    <td class="py-2 pe-4">
                                        @if ($invitation->isUsed())
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Usado</span>
                                        @elseif ($invitation->isExpired())
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Expirado</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="py-2 pe-4">
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

                <div>{{ $invitations->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
