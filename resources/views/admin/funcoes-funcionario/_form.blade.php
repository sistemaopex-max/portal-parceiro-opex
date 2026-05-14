@props(['funcao' => null, 'tipos'])

@php
    $isEdit = $funcao !== null;
    $selecionados = old('tipo_documento_funcionario_ids', $funcao?->tiposDocumentoExigidos?->pluck('id')->all() ?? []);
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="nome" value="Nome da função" />
        <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full" :value="old('nome', $funcao?->nome)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
    </div>
    <div class="flex items-center gap-2">
        <input type="hidden" name="ativo" value="0">
        <input id="ativo" type="checkbox" name="ativo" value="1" @checked((string) old('ativo', ($funcao?->ativo ?? true) ? '1' : '0') === '1')>
        <x-input-label for="ativo" value="Ativa" class="!mb-0" />
    </div>
    <div>
        <x-input-label value="Documentos exigidos" />
        <div class="mt-2 space-y-2 max-h-48 overflow-y-auto rounded-md border border-gray-200 p-3">
            @forelse ($tipos as $tipo)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="tipo_documento_funcionario_ids[]" value="{{ $tipo->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(in_array((string) $tipo->id, array_map('strval', (array) $selecionados), true))>
                    <span>{{ $tipo->nome }}</span>
                </label>
            @empty
                <p class="text-sm text-gray-500">Cadastre tipos em Tipos documento funcionário.</p>
            @endforelse
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('tipo_documento_funcionario_ids')" />
    </div>
    <div class="flex items-center gap-3">
        <x-primary-button>{{ $isEdit ? 'Salvar' : 'Cadastrar' }}</x-primary-button>
        <a href="{{ route('admin.funcoes-funcionario.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
    </div>
</div>
