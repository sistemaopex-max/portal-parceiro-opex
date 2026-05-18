@php
    $homeUrl = route('admin.dashboard');
    $categoriesUrl = route('admin.categorias.index');
    $partnersUrl = route('admin.parceiros.index');
    $invitationsUrl = route('admin.invitations.index');
    $partnersMenuActive = request()->routeIs(
        'admin.parceiros.*',
        'admin.categorias.*',
        'admin.docs.*',
        'admin.docs-func.*',
        'admin.invitations.*',
    );
    $partnersBtn = $partnersMenuActive
        ? 'bg-white/[0.13] text-white font-semibold border-l-2 border-white'
        : 'text-white/70 hover:bg-white/[0.07] hover:text-white border-l-2 border-transparent';
@endphp

<div class="flex h-full min-h-0 flex-col sidebar-scroll overflow-y-auto">

    {{-- Logo area --}}
    <div class="flex shrink-0 items-center justify-center bg-marino-dark/60 border-b border-white/[0.08] px-5 py-4">
        <a href="{{ $homeUrl }}" class="flex w-full items-center justify-center rounded-lg outline-none focus-visible:ring-2 focus-visible:ring-white/40" @click="sidebarOpen = false; partnersOpen = false">
            <x-application-logo class="h-8 w-auto max-w-[60%]" />
        </a>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4" aria-label="Menu principal">

        {{-- Seção: geral --}}
        <p class="mb-1.5 px-3 text-[10px] font-semibold uppercase tracking-widest text-white/35">Painel</p>
        <x-sidebar-link :href="$homeUrl" :active="request()->routeIs('admin.dashboard')" @click="sidebarOpen = false; partnersOpen = false">
            <svg class="h-[18px] w-[18px] shrink-0 opacity-60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Início</span>
        </x-sidebar-link>

        {{-- Seção: gestão --}}
        <p class="mb-1.5 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-white/35">Gestão</p>

        {{-- Parceiros (accordion) --}}
        <div class="space-y-0.5" @click.outside="partnersOpen = false">
            <button
                type="button"
                @click="partnersOpen = !partnersOpen"
                class="flex w-full items-center gap-3 rounded-lg pl-[10px] pr-3 py-2.5 text-left text-sm transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40 border-l-2 {{ $partnersMenuActive ? 'bg-white/[0.13] text-white font-semibold border-white' : 'text-white/70 hover:bg-white/[0.07] hover:text-white border-transparent' }}"
                :aria-expanded="partnersOpen.toString()"
            >
                <svg class="h-[18px] w-[18px] shrink-0 opacity-60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                <span class="flex-1">Parceiros</span>
                <svg
                    class="h-3.5 w-3.5 shrink-0 text-white/50 transition-transform duration-200"
                    :style="{ transform: partnersOpen ? 'rotate(180deg)' : 'rotate(0deg)' }"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div
                x-show="partnersOpen"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-cloak
                class="mt-0.5 space-y-0.5 border-l border-white/20 ml-5 pl-3 py-1"
            >
                <a
                    href="{{ $partnersUrl }}"
                    @click="sidebarOpen = false"
                    class="block rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs('admin.parceiros.*') ? 'bg-white/[0.10] text-white font-medium' : 'text-white/65 hover:bg-white/[0.07] hover:text-white' }}"
                >Gerenciar</a>
                <a
                    href="{{ $categoriesUrl }}"
                    @click="sidebarOpen = false"
                    class="block rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs('admin.categorias.*') ? 'bg-white/[0.10] text-white font-medium' : 'text-white/65 hover:bg-white/[0.07] hover:text-white' }}"
                >Categorias</a>
                <a
                    href="{{ $invitationsUrl }}"
                    @click="sidebarOpen = false"
                    class="block rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs('admin.invitations.*') ? 'bg-white/[0.10] text-white font-medium' : 'text-white/65 hover:bg-white/[0.07] hover:text-white' }}"
                >Convites</a>
            </div>
        </div>
    </nav>
</div>
