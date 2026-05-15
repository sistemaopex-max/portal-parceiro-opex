<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro via convite — {{ config('app.name', 'Portal') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-900">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ===== Painel esquerdo (marca) ===== --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-2/5 flex-col justify-between bg-slate-900 px-12 py-16">
        <div>
            <x-application-logo class="h-10 w-auto brightness-0 invert" />
        </div>

        <div class="space-y-6">
            <div>
                <span class="inline-block rounded-full bg-white/10 px-3 py-1 text-xs font-semibold tracking-widest text-white/70 uppercase mb-4">
                    Convite de cadastro
                </span>
                <h1 class="text-3xl font-bold text-white leading-snug">
                    Bem-vindo(a) ao<br>Portal do Parceiro
                </h1>
                <p class="mt-4 text-base text-white/60 leading-relaxed">
                    Você foi convidado(a) para cadastrar sua empresa como
                    prestador(a) <strong class="text-white/80">{{ $invitation->category?->nome }}</strong>.
                    Preencha o formulário ao lado para criar seu acesso.
                </p>
            </div>

            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-500/20">
                        <svg class="h-3.5 w-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm text-white/60">Um login para todas as suas filiais</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-500/20">
                        <svg class="h-3.5 w-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm text-white/60">Gerencie documentos e funcionários pelo portal</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-500/20">
                        <svg class="h-3.5 w-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm text-white/60">Adicione novas filiais a qualquer momento</p>
                </div>
            </div>
        </div>

        <p class="text-xs text-white/30">
            Convite expira em {{ $invitation->expira_em->format('d/m/Y \à\s H:i') }}
        </p>
    </div>

    {{-- ===== Painel direito (formulário) ===== --}}
    <div class="flex-1 flex flex-col justify-center bg-white lg:rounded-l-3xl overflow-y-auto">
        <div class="w-full max-w-lg mx-auto px-8 py-12">

            {{-- Logo mobile --}}
            <div class="flex justify-center mb-8 lg:hidden">
                <x-application-logo class="h-10 w-auto" />
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Crie sua conta</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Preencha os dados abaixo para concluir seu cadastro.
                </p>
            </div>

            @if ($errors->has('invitation'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    {{ $errors->first('invitation') }}
                </div>
            @endif

            <form method="POST" action="{{ route('invitation.store', $token) }}" class="space-y-8">
                @csrf

                {{-- ===== Dados de acesso ===== --}}
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Dados de acesso</h3>
                    <div class="space-y-4">

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $invitation->email) }}"
                                readonly
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm shadow-sm cursor-not-allowed focus:outline-none"
                            >
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Senha <span class="text-red-500">*</span></label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autofocus
                                    autocomplete="new-password"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-300 @enderror"
                                >
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar senha <span class="text-red-500">*</span></label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ===== Dados da empresa ===== --}}
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Dados da empresa</h3>
                    <div class="space-y-4">

                        <div>
                            <label for="razao_social" class="block text-sm font-medium text-gray-700 mb-1">Razão social <span class="text-red-500">*</span></label>
                            <input
                                id="razao_social"
                                type="text"
                                name="razao_social"
                                value="{{ old('razao_social') }}"
                                required
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('razao_social') border-red-300 @enderror"
                            >
                            @error('razao_social')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
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
                                <label for="cnpj" class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                                <input
                                    id="cnpj"
                                    type="text"
                                    name="cnpj"
                                    value="{{ old('cnpj') ? \App\Models\Partner::formatarCnpj(old('cnpj')) : '' }}"
                                    placeholder="00.000.000/0000-00"
                                    maxlength="18"
                                    inputmode="numeric"
                                    :class="cnpjError ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
                                    class="block w-full rounded-lg text-sm shadow-sm @error('cnpj') border-red-300 @enderror"
                                    x-on:input="$event.target.value = mask($event.target.value); validate($event.target.value)"
                                    x-on:blur="validate($event.target.value)"
                                >
                                <p x-show="cnpjError" x-text="cnpjError" class="mt-1 text-xs text-red-600"></p>
                            </div>
                            <div>
                                <label for="telefone" class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                                <input
                                    id="telefone"
                                    type="text"
                                    name="telefone"
                                    value="{{ old('telefone') }}"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="endereco" class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                            <input
                                id="endereco"
                                type="text"
                                name="endereco"
                                value="{{ old('endereco') }}"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label for="cidade" class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                                <input
                                    id="cidade"
                                    type="text"
                                    name="cidade"
                                    value="{{ old('cidade') }}"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                            <div>
                                <label for="uf" class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                                <input
                                    id="uf"
                                    type="text"
                                    name="uf"
                                    value="{{ old('uf') }}"
                                    maxlength="2"
                                    placeholder="SP"
                                    class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                        </div>

                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full flex justify-center items-center gap-2 rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Criar conta e cadastrar empresa
                </button>

            </form>
        </div>
    </div>

</div>

</body>
</html>
