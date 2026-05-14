@props(['tipo' => null])

@php $isEdit = $tipo !== null; @endphp

<div class="space-y-6">
    <div>
        <x-input-label for="nome" value="Nome do tipo" />
        <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full" :value="old('nome', $tipo?->nome)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
    </div>
    <div class="flex items-center gap-2">
        <input type="hidden" name="ativo" value="0">
        <input id="ativo" type="checkbox" name="ativo" value="1" @checked((string) old('ativo', ($tipo?->ativo ?? true) ? '1' : '0') === '1')>
        <x-input-label for="ativo" value="Ativo" class="!mb-0" />
    </div>
    <div class="flex items-center gap-3">
        <x-primary-button>{{ $isEdit ? 'Salvar' : 'Cadastrar' }}</x-primary-button>
        <a href="{{ route('admin.tipos-documento-funcionario.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
    </div>
</div>
