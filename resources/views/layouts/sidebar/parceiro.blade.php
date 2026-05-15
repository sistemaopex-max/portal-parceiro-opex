@php
    $homeUrl = route('parceiro.dashboard');
    $currentPartner = auth()->user()?->currentPartner();
    $allFiliais = auth()->user()?->partners()->orderBy('razao_social')->get() ?? collect();
@endphp

<div class="flex h-full min-h-0 flex-col text-white">
    <div class="flex shrink-0 items-center justify-center border-b border-white/15 px-4 py-3">
        <a href="{{ $homeUrl }}" class="flex w-full items-center justify-center rounded-lg outline-none focus-visible:ring-2 focus-visible:ring-white/50" @click="sidebarOpen = false; partnersOpen = false">
            <x-application-logo class="my-4 h-7 w-auto max-w-[63%]" />
        </a>
    </div>

    {{-- Filial ativa --}}
    @if ($currentPartner)
        <div class="shrink-0 border-b border-white/15 px-4 py-2">
            @if ($allFiliais->count() > 1)
                <div class="space-y-0.5" x-data="{ filiaisOpen: false }" @click.outside="filiaisOpen = false">
                    <button
                        type="button"
                        @click="filiaisOpen = !filiaisOpen"
                        class="flex w-full items-center gap-2 rounded-lg px-2 py-1.5 text-left text-xs text-white/90 hover:bg-white/10 hover:text-white transition"
                    >
                        <svg class="h-3.5 w-3.5 shrink-0 text-white/60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                        <span class="truncate flex-1 font-medium">{{ $currentPartner->razao_social }}</span>
                        <svg class="h-3 w-3 text-white/60 transition-transform duration-150" :style="{ transform: filiaisOpen ? 'rotate(180deg)' : 'rotate(0)' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="filiaisOpen" x-transition x-cloak class="mt-1 space-y-0.5 border-l border-white/25 py-1 ps-3 ms-3">
                        @foreach ($allFiliais as $filial)
                            @if ($filial->id !== $currentPartner->id)
                                <form method="POST" action="{{ route('parceiro.filiais.switch', $filial) }}">
                                    @csrf
                                    <button type="submit" class="block w-full rounded-md px-2 py-1 text-left text-xs text-white/80 hover:bg-white/10 hover:text-white transition">
                                        {{ $filial->razao_social }}
                                    </button>
                                </form>
                            @else
                                <span class="block rounded-md px-2 py-1 text-xs font-semibold text-white bg-white/10">
                                    {{ $filial->razao_social }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex items-center gap-2 px-2 py-1.5 text-xs text-white/80">
                    <svg class="h-3.5 w-3.5 shrink-0 text-white/60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    <span class="truncate font-medium text-white">{{ $currentPartner->razao_social }}</span>
                </div>
            @endif
        </div>
    @endif

    <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain p-3" aria-label="Menu principal">
        <x-sidebar-link :href="$homeUrl" :active="request()->routeIs('parceiro.dashboard')" @click="sidebarOpen = false; partnersOpen = false">
            <svg class="h-5 w-5 shrink-0 text-white/55 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Início</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('parceiro.empresa.documentos.index')" :active="request()->routeIs('parceiro.empresa.documentos.*')" @click="sidebarOpen = false">
            <svg class="h-5 w-5 shrink-0 text-white/55 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Docs empresa</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('parceiro.funcionarios.index')" :active="request()->routeIs('parceiro.funcionarios.*')" @click="sidebarOpen = false">
            <svg class="h-5 w-5 shrink-0 text-white/55 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span>Funcionários</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('parceiro.filiais.index')" :active="request()->routeIs('parceiro.filiais.*')" @click="sidebarOpen = false">
            <svg class="h-5 w-5 shrink-0 text-white/55 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
            </svg>
            <span>Filiais</span>
        </x-sidebar-link>
    </nav>

    @include('layouts.sidebar._footer')
</div>
