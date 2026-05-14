@props(['category' => null, 'tiposDocumentoEmpresa' => null])

@php
    $isEdit = $category !== null;
    $tipos = $tiposDocumentoEmpresa ?? collect();
    $selecionados = old('tipo_documento_empresa_ids', $category?->tiposDocumentoExigidos?->pluck('id')->all() ?? []);
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="name" value="Nome" />
        <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name', $category?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="slug" value="Slug (opcional)" />
        <x-text-input id="slug" name="slug" type="text" class="block mt-1 w-full font-mono" :value="old('slug', $category?->slug)" placeholder="ex.: distribuidor" />
        <p class="mt-1 text-sm text-gray-500">Apenas letras minúsculas, números e hífens. Se vazio, será gerado a partir do nome.</p>
        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
    </div>

    <div>
        <x-input-label for="description" value="Descrição (opcional)" />
        <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $category?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div>
        <x-input-label value="Documentos exigidos da empresa (opcional)" />
        <div class="mt-2 space-y-2 max-h-48 overflow-y-auto rounded-md border border-gray-200 p-3">
            @forelse ($tipos as $tipo)
                <label class="flex items-center gap-2 text-sm">
                    <input
                        type="checkbox"
                        name="tipo_documento_empresa_ids[]"
                        value="{{ $tipo->id }}"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        @checked(in_array((string) $tipo->id, array_map('strval', (array) $selecionados), true))
                    >
                    <span>{{ $tipo->nome }}@if (! $tipo->ativo) <span class="text-gray-400">(inativo)</span>@endif</span>
                </label>
            @empty
                <p class="text-sm text-gray-500">Cadastre tipos de documento em <span class="font-medium">Tipos documento empresa</span>.</p>
            @endforelse
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('tipo_documento_empresa_ids')" />
        <x-input-error class="mt-2" :messages="$errors->get('tipo_documento_empresa_ids.*')" />
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input id="is_active" type="checkbox" name="is_active" value="1" @checked((string) old('is_active', ($category?->is_active ?? true) ? '1' : '0') === '1')>
        <x-input-label for="is_active" value="Categoria ativa" class="!mb-0" />
    </div>

    <div class="flex items-center gap-3">
        <x-primary-button>{{ $isEdit ? 'Salvar' : 'Cadastrar' }}</x-primary-button>
        <a href="{{ route('admin.partner-categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
    </div>
</div>
