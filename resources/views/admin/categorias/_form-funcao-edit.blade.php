@props(['category', 'funcao'])

<div class="space-y-6">
    <input type="hidden" name="funcao_id" value="{{ $funcao->id }}">

    <div>
        <x-input-label for="nome" value="Nome da função" />
        <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full" :value="old('nome', $funcao->nome)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="ativo" value="0">
        <input id="ativo" type="checkbox" name="ativo" value="1" @checked(old('ativo', $funcao->ativo))>
        <x-input-label for="ativo" value="Função ativa" class="!mb-0" />
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <x-config-action-btn type="submit" variant="primary">Salvar</x-config-action-btn>
        <x-config-action-btn :href="route('admin.categorias.funcoes.index', $category)" variant="default">Cancelar</x-config-action-btn>
    </div>
</div>
