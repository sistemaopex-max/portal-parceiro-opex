@props(['category' => null])

@php
    $isEdit = $category !== null;
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="name" value="Nome" />
        <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name', $category?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="description" value="Descrição (opcional)" />
        <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $category?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input id="is_active" type="checkbox" name="is_active" value="1" @checked((string) old('is_active', ($category?->is_active ?? true) ? '1' : '0') === '1')>
        <x-input-label for="is_active" value="Categoria ativa" class="!mb-0" />
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <x-config-action-btn type="submit" variant="primary">{{ $isEdit ? 'Salvar' : 'Cadastrar' }}</x-config-action-btn>
        <x-config-action-btn :href="route('admin.partner-categories.index')" variant="default">Cancelar</x-config-action-btn>
    </div>
</div>
