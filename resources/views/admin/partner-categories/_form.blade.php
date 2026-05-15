@props(['category' => null])

@php
    $isEdit = $category !== null;
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="nome" value="Nome" />
        <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full" :value="old('nome', $category?->nome)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
    </div>

    <div>
        <x-input-label for="descricao" value="Descrição (opcional)" />
        <textarea id="descricao" name="descricao" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descricao', $category?->descricao) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="ativo" value="0">
        <input id="ativo" type="checkbox" name="ativo" value="1" @checked((string) old('ativo', ($category?->ativo ?? true) ? '1' : '0') === '1')>
        <x-input-label for="ativo" value="Categoria ativa" class="!mb-0" />
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <x-config-action-btn type="submit" variant="primary">{{ $isEdit ? 'Salvar' : 'Cadastrar' }}</x-config-action-btn>
        <x-config-action-btn :href="route('admin.partner-categories.index')" variant="default">Cancelar</x-config-action-btn>
    </div>
</div>
