<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Editar funcionário — {{ $funcionario->nome }}"
            :back="route('parceiro.funcionarios.index')"
            back-label="Funcionários"
        />
    </x-slot>

    <div class="max-w-2xl">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('parceiro.funcionarios.update', $funcionario) }}">
                @csrf
                @method('PUT')
                @include('parceiro.funcionarios._form', [
                    'funcionario' => $funcionario,
                    'funcoes' => $funcoes,
                    'partner' => $partner,
                ])
            </form>
        </div>
    </div>
</x-app-layout>
