<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar filial — {{ $partner->razao_social }}</h2>
    </x-slot>

    <div class="space-y-4">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <a href="{{ route('parceiro.filiais.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Filiais</a>

            <form method="POST" action="{{ route('parceiro.filiais.update', $partner) }}" class="space-y-5 max-w-lg">
                @csrf @method('PUT')
                @include('parceiro.filiais._form', ['filial' => $partner])

                <div class="flex gap-3">
                    <x-config-action-btn type="submit" variant="primary">Salvar alterações</x-config-action-btn>
                    <x-config-action-btn :href="route('parceiro.filiais.index')">Cancelar</x-config-action-btn>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
