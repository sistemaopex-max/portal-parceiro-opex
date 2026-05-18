@php
    $hour = now()->format('H');
    $greeting = $hour < 12 ? 'Bom dia' : ($hour < 18 ? 'Boa tarde' : 'Boa noite');
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Painel administrativo" />
    </x-slot>

    <div class="space-y-8">

        {{-- Banner de boas-vindas --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-marino to-marino-700 px-7 py-7 shadow-card-md">
            <div class="relative z-10">
                <p class="text-sm font-medium text-white/70">{{ $greeting }},</p>
                <h2 class="mt-0.5 text-2xl font-black text-white">{{ auth()->user()->name }}</h2>
                <p class="mt-1.5 text-sm text-white/60">Painel de gestão de parceiros Opex. Última atualização: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            {{-- Decorative circles --}}
            <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/[0.06]"></div>
            <div class="pointer-events-none absolute -bottom-12 right-12 h-56 w-56 rounded-full bg-white/[0.04]"></div>
        </div>

        {{-- Stat cards --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card
                label="Parceiros ativos"
                :value="$totalParceiros"
                color="blue"
                :href="route('admin.parceiros.index')"
            >
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </x-stat-card>

            <x-stat-card
                label="Docs em dia"
                :value="$parceirosDia . ' / ' . $totalParceiros"
                color="green"
            >
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </x-stat-card>

            <x-stat-card
                label="Docs pendentes"
                :value="$docsPendentes"
                :color="$docsPendentes > 0 ? 'yellow' : 'gray'"
                sub="Aguardando validação"
                :href="route('admin.documentos.pendentes')"
            >
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </x-stat-card>

            <x-stat-card
                label="Convites ativos"
                :value="$convitesPendentes"
                color="blue"
                :href="route('admin.invitations.index')"
            >
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
            </x-stat-card>
        </div>

        {{-- Ações rápidas --}}
        <div>
            <h3 class="mb-3 text-xs font-semibold uppercase tracking-widest text-zinc-400">Ações rápidas</h3>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('admin.parceiros.index') }}" class="group flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition hover:shadow-card-hover hover:border-marino/30">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-marino/10 text-marino transition group-hover:bg-marino group-hover:text-white">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-zinc-900">Gerenciar parceiros</p>
                        <p class="mt-0.5 text-sm text-zinc-500">Ver lista, validar documentos</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0 text-zinc-300 transition group-hover:text-marino" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>

                <a href="{{ route('admin.categorias.index') }}" class="group flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition hover:shadow-card-hover hover:border-marino/30">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-marino/10 text-marino transition group-hover:bg-marino group-hover:text-white">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-zinc-900">Categorias</p>
                        <p class="mt-0.5 text-sm text-zinc-500">Documentos e funções exigidos</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0 text-zinc-300 transition group-hover:text-marino" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>

                <a href="{{ route('admin.invitations.create') }}" class="group flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-card transition hover:shadow-card-hover hover:border-marino/30">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-marino/10 text-marino transition group-hover:bg-marino group-hover:text-white">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-zinc-900">Novo convite</p>
                        <p class="mt-0.5 text-sm text-zinc-500">Convidar empresa parceira</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0 text-zinc-300 transition group-hover:text-marino" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
