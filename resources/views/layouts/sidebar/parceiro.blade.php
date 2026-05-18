@php
    $homeUrl = route('parceiro.dashboard');
@endphp

<div class="flex h-full min-h-0 flex-col overflow-visible">

    {{-- Logo area --}}
    <div class="flex shrink-0 items-center justify-center bg-marino-dark/60 border-b border-white/[0.08] px-5 py-4">
        <a href="{{ $homeUrl }}" class="flex w-full items-center justify-center rounded-lg outline-none focus-visible:ring-2 focus-visible:ring-white/40" @click="sidebarOpen = false">
            <x-application-logo class="h-8 w-auto max-w-[60%]" />
        </a>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-scroll min-h-0 flex-1 overflow-y-auto px-3 py-4" aria-label="Menu principal">

        {{-- Portal --}}
        <p class="mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-white/35">Portal</p>

        <x-sidebar-link :href="$homeUrl" :active="request()->routeIs('parceiro.dashboard')" @click="sidebarOpen = false">
            <svg class="h-[18px] w-[18px] shrink-0 opacity-60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Início</span>
        </x-sidebar-link>

        {{-- Documentos --}}
        <p class="mb-1.5 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-white/35">Documentos</p>

        <x-sidebar-link :href="route('parceiro.docs.index')" :active="request()->routeIs('parceiro.docs.index') || request()->routeIs('parceiro.docs.upload') || request()->routeIs('parceiro.docs.download')" @click="sidebarOpen = false">
            <svg class="h-[18px] w-[18px] shrink-0 opacity-60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Empresa</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('parceiro.docs.funcionarios')" :active="request()->routeIs('parceiro.docs.funcionarios') || request()->routeIs('parceiro.funcionarios.docs.*')" @click="sidebarOpen = false">
            <svg class="h-[18px] w-[18px] shrink-0 opacity-60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span>Funcionários</span>
        </x-sidebar-link>

        {{-- Cadastros --}}
        <p class="mb-1.5 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-white/35">Cadastros</p>

        <x-sidebar-link :href="route('parceiro.filiais.index')" :active="request()->routeIs('parceiro.filiais.*')" @click="sidebarOpen = false">
            <svg class="h-[18px] w-[18px] shrink-0 opacity-60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
            </svg>
            <span>Filiais</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('parceiro.funcionarios.index')" :active="request()->routeIs('parceiro.funcionarios.index') || request()->routeIs('parceiro.funcionarios.create') || request()->routeIs('parceiro.funcionarios.edit') || request()->routeIs('parceiro.funcionarios.store') || request()->routeIs('parceiro.funcionarios.update') || request()->routeIs('parceiro.funcionarios.inativar')" @click="sidebarOpen = false">
            <svg class="h-[18px] w-[18px] shrink-0 opacity-60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span>Funcionários</span>
        </x-sidebar-link>

    </nav>

    <div class="relative z-30 shrink-0 overflow-visible border-t border-white/[0.08]">
        @include('layouts.sidebar._filial-selector', ['position' => 'footer'])
    </div>
</div>
