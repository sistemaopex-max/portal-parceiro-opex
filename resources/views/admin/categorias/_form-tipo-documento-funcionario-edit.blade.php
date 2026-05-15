@props(['category', 'funcao', 'tipo'])

<div class="space-y-6">
    <input type="hidden" name="tipo_id" value="{{ $tipo->id }}">

    <div>
        <x-input-label for="nome" value="Nome do documento" />
        <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full" :value="old('nome', $tipo->nome)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="ativo" value="0">
        <input id="ativo" type="checkbox" name="ativo" value="1" @checked((string) old('ativo', $tipo->ativo ? '1' : '0') === '1')>
        <x-input-label for="ativo" value="Documento ativo" class="!mb-0" />
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <x-config-action-btn type="submit" variant="primary">Salvar</x-config-action-btn>
        <x-config-action-btn :href="route('admin.categorias.funcoes.docs.index', [$category, $funcao])" variant="default">Cancelar</x-config-action-btn>
    </div>
</div>
