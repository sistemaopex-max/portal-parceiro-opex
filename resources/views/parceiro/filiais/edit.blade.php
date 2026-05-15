<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Editar filial — {{ $partner->razao_social }}"
            :back="route('parceiro.filiais.index')"
            back-label="Filiais"
        />
    </x-slot>

    <div class="max-w-lg">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('parceiro.filiais.update', $partner) }}" class="space-y-5">
                @csrf @method('PUT')
                @include('parceiro.filiais._form', ['filial' => $partner])
                <div class="flex gap-3 pt-2">
                    <x-config-action-btn type="submit" variant="primary">Salvar alterações</x-config-action-btn>
                    <x-config-action-btn :href="route('parceiro.filiais.index')">Cancelar</x-config-action-btn>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
