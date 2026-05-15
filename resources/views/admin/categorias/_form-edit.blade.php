@props(['category'])

<div class="space-y-6">
    <div>
        <x-input-label for="nome" value="Nome" />
        <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full" :value="old('nome', $category->nome)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
    </div>

    <div>
        <x-input-label for="descricao" value="Descrição (opcional)" />
        <textarea id="descricao" name="descricao" rows="4" class="block mt-1 w-full border-gray-300 focus:border-marino focus:ring-marino rounded-md shadow-sm">{{ old('descricao', $category->descricao) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <x-config-action-btn type="submit" variant="primary">Salvar</x-config-action-btn>
        <x-config-action-btn :href="route('admin.categorias.show', $category)" variant="default">Cancelar</x-config-action-btn>
    </div>
</div>
