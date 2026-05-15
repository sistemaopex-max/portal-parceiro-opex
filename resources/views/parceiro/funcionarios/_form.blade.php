@props(['funcionario' => null, 'funcoes', 'partner'])

<div class="space-y-5">

    {{-- Empresa (readonly) --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
        <input
            type="text"
            value="{{ $partner->razao_social }}"
            disabled
            class="block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 text-sm shadow-sm cursor-not-allowed"
        >
    </div>

    {{-- Nome --}}
    <div>
        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome completo <span class="text-red-500">*</span></label>
        <input
            id="nome"
            type="text"
            name="nome"
            value="{{ old('nome', $funcionario?->nome) }}"
            required
            class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nome') border-red-300 @enderror"
        >
        @error('nome')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Data de nascimento --}}
    <div>
        <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-1">Data de nascimento</label>
        <input
            id="data_nascimento"
            type="date"
            name="data_nascimento"
            value="{{ old('data_nascimento', $funcionario?->data_nascimento?->format('Y-m-d')) }}"
            max="{{ now()->subDay()->format('Y-m-d') }}"
            class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('data_nascimento') border-red-300 @enderror"
        >
        @error('data_nascimento')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- CPF com máscara --}}
    <div x-data="{
            cpfError: '{{ $errors->first('cpf') }}',
            mask(val) {
                let v = val.replace(/\D/g, '').slice(0, 11);
                if (v.length > 9) return v.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
                if (v.length > 6) return v.replace(/^(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
                if (v.length > 3) return v.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
                return v;
            },
            getRaw(val) {
                return val.replace(/\D/g, '');
            }
        }">
        <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">CPF <span class="text-red-500">*</span></label>
        <input
            id="cpf_display"
            type="text"
            inputmode="numeric"
            maxlength="14"
            placeholder="000.000.000-00"
            value="{{ old('cpf') ? preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', old('cpf')) : ($funcionario?->cpf ? preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $funcionario->cpf) : '') }}"
            :class="cpfError ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
            class="block w-full rounded-md text-sm shadow-sm @error('cpf') border-red-300 @enderror"
            x-on:input="$event.target.value = mask($event.target.value); $refs.cpfHidden.value = getRaw($event.target.value); cpfError = ''"
        >
        <input
            id="cpf"
            type="hidden"
            name="cpf"
            x-ref="cpfHidden"
            value="{{ old('cpf', $funcionario?->cpf) }}"
        >
        <p x-show="cpfError" x-text="cpfError" class="mt-1 text-xs text-red-600"></p>
        @error('cpf')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Função --}}
    <div>
        <label for="funcao_funcionario_id" class="block text-sm font-medium text-gray-700 mb-1">Função <span class="text-red-500">*</span></label>
        <select
            id="funcao_funcionario_id"
            name="funcao_funcionario_id"
            required
            class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('funcao_funcionario_id') border-red-300 @enderror"
        >
            <option value="">Selecione uma função</option>
            @foreach ($funcoes as $funcao)
                <option
                    value="{{ $funcao->id }}"
                    @selected((string) old('funcao_funcionario_id', $funcionario?->funcao_funcionario_id) === (string) $funcao->id)
                >{{ $funcao->nome }}</option>
            @endforeach
        </select>
        @error('funcao_funcionario_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3 pt-2">
        <x-primary-button>Salvar</x-primary-button>
        <a href="{{ route('parceiro.funcionarios.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
    </div>

</div>
