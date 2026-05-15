<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrar — {{ config('app.name', 'Portal') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-900">

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ===== Painel esquerdo ===== --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-2/5 flex-col justify-between bg-slate-900 px-12 py-16">
        <div>
            <x-application-logo class="h-10 w-auto brightness-0 invert" />
        </div>

        <div class="space-y-6">
            <div>
                <span class="inline-block rounded-full bg-white/10 px-3 py-1 text-xs font-semibold tracking-widest text-white/70 uppercase mb-4">
                    Portal do Parceiro
                </span>
                <h1 class="text-3xl font-bold text-white leading-snug">
                    Bem-vindo(a) de<br>volta à Opex
                </h1>
                <p class="mt-4 text-base text-white/60 leading-relaxed">
                    Acesse o portal para gerenciar seus documentos,
                    funcionários e filiais em um só lugar.
                </p>
            </div>

            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-500/20">
                        <svg class="h-3.5 w-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm text-white/60">Gestão de documentos da empresa</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-500/20">
                        <svg class="h-3.5 w-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm text-white/60">Cadastro e documentação de funcionários</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-500/20">
                        <svg class="h-3.5 w-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm text-white/60">Múltiplas filiais com um único acesso</p>
                </div>
            </div>
        </div>

        <p class="text-xs text-white/30">
            © {{ date('Y') }} Opex Ltda. Todos os direitos reservados.
        </p>
    </div>

    {{-- ===== Painel direito (formulário) ===== --}}
    <div class="flex-1 flex flex-col justify-center bg-white lg:rounded-l-3xl">
        <div class="w-full max-w-lg mx-auto px-12 py-14">

            {{-- Logo mobile --}}
            <div class="flex justify-center mb-8 lg:hidden">
                <x-application-logo class="h-12 w-auto" />
            </div>

            <div class="mb-8">
                <h2 class="text-4xl font-bold text-gray-900">Entrar na sua conta</h2>
                <p class="mt-2 text-lg text-gray-500">
                    Informe seus dados de acesso para continuar.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-base text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('invitation'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-base text-red-700">
                    {{ $errors->first('invitation') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-lg font-medium text-gray-600 mb-2">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="block w-full rounded-lg border-gray-300 text-lg py-3.5 px-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-300 @enderror"
                        placeholder="seu@email.com"
                    >
                    @error('email')
                        <p class="mt-1.5 text-base text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-lg font-medium text-gray-600">Senha</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-base text-indigo-600 hover:text-indigo-500">
                                Esqueceu a senha?
                            </a>
                        @endif
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="block w-full rounded-lg border-gray-300 text-lg py-3.5 px-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-300 @enderror"
                    >
                    @error('password')
                        <p class="mt-1.5 text-base text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-5 w-5"
                    >
                    <label for="remember_me" class="text-lg text-gray-500">Lembrar de mim</label>
                </div>

                <button
                    type="submit"
                    class="w-full flex justify-center items-center gap-2 rounded-lg bg-indigo-600 px-4 py-4 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Entrar
                </button>
            </form>
        </div>
    </div>

</div>

</body>
</html>
