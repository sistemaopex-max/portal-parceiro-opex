@php $filial = $filial ?? null; @endphp

<div>
    <label for="razao_social" class="block text-sm font-medium text-gray-700">Razão social <span class="text-red-500">*</span></label>
    <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social', $filial?->razao_social) }}" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-marino focus:ring-marino text-sm @error('razao_social') border-red-300 @enderror">
    @error('razao_social')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div x-data="{
        cnpjError: '{{ $errors->first('cnpj') }}',
        mask(val) {
            let v = val.replace(/\D/g, '').slice(0, 14);
            if (v.length > 12) return v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})/, '$1.$2.$3/$4-$5');
            if (v.length > 8)  return v.replace(/^(\d{2})(\d{3})(\d{3})(\d{0,4})/, '$1.$2.$3/$4');
            if (v.length > 5)  return v.replace(/^(\d{2})(\d{3})(\d{0,3})/, '$1.$2.$3');
            if (v.length > 2)  return v.replace(/^(\d{2})(\d{0,3})/, '$1.$2');
            return v;
        },
        validate(val) {
            const d = val.replace(/\D/g, '');
            if (d.length === 0) { this.cnpjError = ''; return; }
            if (d.length < 14) { this.cnpjError = 'CNPJ incompleto.'; return; }
            if (/^(\d)\1{13}$/.test(d)) { this.cnpjError = 'CNPJ inválido.'; return; }
            const calc = (n) => {
                const w = n === 1 ? [5,4,3,2,9,8,7,6,5,4,3,2] : [6,5,4,3,2,9,8,7,6,5,4,3,2];
                const sum = w.reduce((acc, p, i) => acc + parseInt(d[i]) * p, 0);
                const r = sum % 11;
                return r < 2 ? 0 : 11 - r;
            };
            if (calc(1) !== parseInt(d[12]) || calc(2) !== parseInt(d[13])) {
                this.cnpjError = 'CNPJ inválido.';
            } else {
                this.cnpjError = '';
            }
        }
    }">
    <label for="cnpj" class="block text-sm font-medium text-gray-700">CNPJ</label>
    <input
        type="text"
        id="cnpj"
        name="cnpj"
        value="{{ old('cnpj', \App\Models\Partner::formatarCnpj($filial?->cnpj)) }}"
        placeholder="00.000.000/0000-00"
        maxlength="18"
        inputmode="numeric"
        :class="cnpjError ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-marino focus:ring-marino'"
        class="mt-1 block w-full rounded-md text-sm shadow-sm"
        x-on:input="$event.target.value = mask($event.target.value); validate($event.target.value)"
        x-on:blur="validate($event.target.value)"
    >
    <p x-show="cnpjError" x-text="cnpjError" class="mt-1 text-xs text-red-600"></p>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
        <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $filial?->telefone) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-marino focus:ring-marino text-sm">
        @error('telefone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">E-mail da empresa</label>
        <input type="email" id="email" name="email" value="{{ old('email', $filial?->email) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-marino focus:ring-marino text-sm">
        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço</label>
    <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $filial?->endereco) }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-marino focus:ring-marino text-sm">
    @error('endereco')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-3 gap-4">
    <div class="col-span-2">
        <label for="cidade" class="block text-sm font-medium text-gray-700">Cidade</label>
        <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $filial?->cidade) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-marino focus:ring-marino text-sm">
        @error('cidade')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="uf" class="block text-sm font-medium text-gray-700">UF</label>
        <input type="text" id="uf" name="uf" value="{{ old('uf', $filial?->uf) }}" maxlength="2" placeholder="SP"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-marino focus:ring-marino text-sm">
        @error('uf')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
