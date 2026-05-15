<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Painel do parceiro" subtitle="Bem-vindo(a), {{ auth()->user()->name }}" />
    </x-slot>

    <div class="space-y-8">

        @if (session('status'))
            <x-alert>{{ session('status') }}</x-alert>
        @endif

        {{-- Empresa info --}}
        @if ($partner)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $partner->razao_social }}</p>
                        <p class="mt-0.5 text-sm text-gray-500">
                            {{ $partner->category?->nome }}
                            @if ($partner->cnpj_formatado)
                                &bull; {{ $partner->cnpj_formatado }}
                            @endif
                            @if ($partner->cidade)
                                &bull; {{ $partner->cidade }}{{ $partner->uf ? '/' . $partner->uf : '' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Stat cards --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card
                label="Docs empresa enviados"
                :value="$docsEnviados . ' / ' . $totalDocs"
                :color="$docsEnviados === $totalDocs && $totalDocs > 0 ? 'green' : 'yellow'"
                :href="route('parceiro.docs.index')"
            >
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </x-stat-card>

            <x-stat-card
                label="Docs pendentes"
                :value="$docsPendentes"
                :color="$docsPendentes > 0 ? 'yellow' : 'gray'"
                sub="Aguardando validação"
            >
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </x-stat-card>

            <x-stat-card
                label="Funcionários"
                :value="$totalFuncionarios"
                color="blue"
                :href="route('parceiro.funcionarios.index')"
            >
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </x-stat-card>

            <x-stat-card
                label="Funcs. docs em dia"
                :value="$funcDia . ' / ' . $totalFuncionarios"
                :color="$totalFuncionarios > 0 && $funcDia === $totalFuncionarios ? 'green' : ($funcDia > 0 ? 'yellow' : 'gray')"
            >
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </x-stat-card>
        </div>

        {{-- Ações rápidas --}}
        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('parceiro.docs.index') }}" class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-marino/30">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-marino/10 text-marino group-hover:bg-marino group-hover:text-white transition">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Documentos da empresa</p>
                    <p class="mt-0.5 text-sm text-gray-500">Enviar ou atualizar arquivos</p>
                </div>
            </a>

            <a href="{{ route('parceiro.funcionarios.create') }}" class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-marino/30">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-marino/10 text-marino group-hover:bg-marino group-hover:text-white transition">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Cadastrar funcionário</p>
                    <p class="mt-0.5 text-sm text-gray-500">Adicionar e enviar documentos</p>
                </div>
            </a>
        </div>

    </div>
</x-app-layout>
