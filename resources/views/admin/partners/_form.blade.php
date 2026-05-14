@props(['partner', 'categories'])

@php
    $user = $partner->user;
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="name" value="Nome do contato" />
        <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name', $user?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="email" value="E-mail (acesso)" />
        <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $user?->email)" required autocomplete="username" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="password" value="Nova senha (opcional)" />
        <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" autocomplete="new-password" />
        <p class="mt-1 text-sm text-gray-500">Deixe em branco para manter a senha atual.</p>
        <x-input-error class="mt-2" :messages="$errors->get('password')" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Confirmar senha" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full" autocomplete="new-password" />
        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
    </div>

    <div>
        <x-input-label for="razao_social" value="Razão social" />
        <x-text-input id="razao_social" name="razao_social" type="text" class="block mt-1 w-full" :value="old('razao_social', $partner?->razao_social)" required />
        <x-input-error class="mt-2" :messages="$errors->get('razao_social')" />
    </div>

    <div>
        <x-input-label for="cnpj" value="CNPJ (opcional)" />
        <x-text-input id="cnpj" name="cnpj" type="text" class="block mt-1 w-full font-mono" :value="old('cnpj', $partner?->cnpj)" maxlength="18" />
        <x-input-error class="mt-2" :messages="$errors->get('cnpj')" />
    </div>

    <div>
        <x-input-label for="telefone" value="Telefone (opcional)" />
        <x-text-input id="telefone" name="telefone" type="text" class="block mt-1 w-full" :value="old('telefone', $partner?->telefone)" />
        <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
    </div>

    <div>
        <x-input-label for="endereco" value="Endereço (opcional)" />
        <x-text-input id="endereco" name="endereco" type="text" class="block mt-1 w-full" :value="old('endereco', $partner?->endereco)" maxlength="500" autocomplete="street-address" />
        <x-input-error class="mt-2" :messages="$errors->get('endereco')" />
    </div>

    <div>
        <x-input-label for="cidade" value="Cidade (opcional)" />
        <x-text-input id="cidade" name="cidade" type="text" class="block mt-1 w-full" :value="old('cidade', $partner?->cidade)" maxlength="120" />
        <x-input-error class="mt-2" :messages="$errors->get('cidade')" />
    </div>

    <div>
        <x-input-label for="uf" value="UF (opcional)" />
        <x-text-input id="uf" name="uf" type="text" class="block mt-1 w-full max-w-[5rem] font-mono uppercase" :value="old('uf', $partner?->uf)" maxlength="2" placeholder="SP" />
        <x-input-error class="mt-2" :messages="$errors->get('uf')" />
    </div>

    <div>
        <x-input-label for="categoria_id" value="Categoria" />
        <select id="categoria_id" name="categoria_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="" disabled @selected(old('categoria_id', $partner?->categoria_id) === null)>Selecione…</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('categoria_id', $partner?->categoria_id) === (string) $category->id)>
                    {{ $category->name }}@if (! $category->is_active) (inativa)@endif
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('categoria_id')" />
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="ativo" value="0">
        <input id="ativo" type="checkbox" name="ativo" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked(filter_var(old('ativo', $partner->ativo), FILTER_VALIDATE_BOOLEAN))>
        <x-input-label for="ativo" value="Parceiro ativo" class="!mb-0" />
    </div>
    <x-input-error class="mt-2" :messages="$errors->get('ativo')" />

    <div class="flex items-center gap-3">
        <x-primary-button>Salvar</x-primary-button>
        <a href="{{ route('admin.parceiros.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
    </div>
</div>
