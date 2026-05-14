@props(['funcionario' => null, 'funcoes'])

<div class="space-y-6">
    <div>
        <x-input-label for="nome" value="Nome" />
        <x-text-input id="nome" name="nome" type="text" class="block mt-1 w-full" :value="old('nome', $funcionario?->nome)" required />
        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
    </div>
    <div>
        <x-input-label for="cpf" value="CPF (apenas números)" />
        <x-text-input id="cpf" name="cpf" type="text" class="block mt-1 w-full font-mono" :value="old('cpf', $funcionario?->cpf)" maxlength="14" required />
        <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
    </div>
    <div>
        <x-input-label for="funcao_funcionario_id" value="Função" />
        <select id="funcao_funcionario_id" name="funcao_funcionario_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            @foreach ($funcoes as $funcao)
                <option value="{{ $funcao->id }}" @selected((string) old('funcao_funcionario_id', $funcionario?->funcao_funcionario_id) === (string) $funcao->id)>{{ $funcao->nome }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('funcao_funcionario_id')" />
    </div>
    <div class="flex items-center gap-3">
        <x-primary-button>Salvar</x-primary-button>
        <a href="{{ route('parceiro.funcionarios.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
    </div>
</div>
