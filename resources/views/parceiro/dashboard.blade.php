@php
    $hour = now()->format('H');
    $greeting = $hour < 12 ? 'Bom dia' : ($hour < 18 ? 'Boa tarde' : 'Boa noite');
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Painel do parceiro" />
    </x-slot>

    <div class="space-y-8">

        @if (session('status'))
            <x-alert>{{ session('status') }}</x-alert>
        @endif

        {{-- Banner de boas-vindas --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-marino to-marino-700 px-7 py-7 shadow-card-md">
            <div class="relative z-10">
                <p class="text-sm font-medium text-white/70">{{ $greeting }},</p>
                <h2 class="mt-0.5 text-2xl font-black text-white">{{ auth()->user()->name }}</h2>
                @if ($partner)
                    <p class="mt-1.5 text-sm text-white/60">
                        {{ $partner->razao_social }}
                        @if ($partner->category)
                            &bull; {{ $partner->category->nome }}
                        @endif
                        @if ($partner->cidade)
                            &bull; {{ $partner->cidade }}{{ $partner->uf ? '/' . $partner->uf : '' }}
                        @endif
                    </p>
                @endif
            </div>
            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/[0.06]"></div>
            <div class="pointer-events-none absolute -bottom-12 right-12 h-56 w-56 rounded-full bg-white/[0.04]"></div>
        </div>

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
        <div>
            <h3 class="mb-3 text-xs font-semibold uppercase tracking-widest text-zinc-400">Ações rápidas</h3>
            <div class="grid gap-3 sm:grid-cols-2">
                <a href="{{ route('parceiro.docs.index') }}" class="group flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition hover:shadow-card-hover hover:border-marino/30">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-marino/10 text-marino transition group-hover:bg-marino group-hover:text-white">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-zinc-900">Documentos da empresa</p>
                        <p class="mt-0.5 text-sm text-zinc-500">Enviar ou atualizar arquivos</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0 text-zinc-300 transition group-hover:text-marino" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>

                <a href="{{ route('parceiro.funcionarios.create') }}" class="group flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition hover:shadow-card-hover hover:border-marino/30">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-marino/10 text-marino transition group-hover:bg-marino group-hover:text-white">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-zinc-900">Cadastrar funcionário</p>
                        <p class="mt-0.5 text-sm text-zinc-500">Adicionar e enviar documentos</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0 text-zinc-300 transition group-hover:text-marino" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
