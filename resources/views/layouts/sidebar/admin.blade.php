@php
    $homeUrl = route('admin.dashboard');
@endphp

<div class="flex h-full min-h-0 flex-col text-white">
    <div class="flex shrink-0 items-center justify-center border-b border-white/15 px-4 py-3">
        <a href="{{ $homeUrl }}" class="flex w-full items-center justify-center rounded-lg outline-none focus-visible:ring-2 focus-visible:ring-white/50" @click="sidebarOpen = false; partnersOpen = false">
            <x-application-logo class="my-4 h-7 w-auto max-w-[63%]" />
        </a>
    </div>

    <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain p-3" aria-label="Menu principal">
        <x-sidebar-link :href="$homeUrl" :active="request()->routeIs('admin.dashboard')" @click="sidebarOpen = false; partnersOpen = false">
            <svg class="h-5 w-5 shrink-0 text-white/55 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Início</span>
        </x-sidebar-link>

        @php
            $partnersActive = request()->routeIs(
                'admin.parceiros.*',
                'admin.partner-categories.*',
                'admin.tipos-documento-empresa.*',
                'admin.tipos-documento-funcionario.*',
                'admin.funcoes-funcionario.*',
                'admin.documentos-empresa.*',
                'admin.documentos-funcionario.*',
            );
            $partnersBtn = $partnersActive
                ? 'bg-white/15 text-white shadow-sm ring-1 ring-inset ring-white/20'
                : 'text-white/90 hover:bg-white/10 hover:text-white';
        @endphp
        <div class="space-y-0.5" @click.outside="partnersOpen = false">
            <button
                type="button"
                @click="partnersOpen = !partnersOpen"
                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40 {{ $partnersBtn }}"
                :aria-expanded="partnersOpen"
            >
                <svg class="h-5 w-5 shrink-0 text-white/55" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                <span>Parceiros</span>
                <span
                    class="ms-auto inline-flex h-4 w-4 shrink-0 items-center justify-center text-white/70 transition-transform duration-200 ease-out will-change-transform"
                    x-bind:style="{ transform: partnersOpen ? 'rotate(180deg)' : 'rotate(0deg)' }"
                    aria-hidden="true"
                >
                    <svg
                        class="h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </span>
            </button>
            <div
                x-show="partnersOpen"
                x-transition
                x-cloak
                class="mt-0.5 space-y-0.5 border-l border-white/25 py-1 ps-3 ms-3"
            >
                <a
                    href="{{ route('admin.parceiros.index') }}"
                    class="block rounded-md px-2 py-1.5 text-sm hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.parceiros.index', 'admin.parceiros.edit') ? 'bg-white/10 text-white font-medium' : 'text-white/90' }}"
                    @click="sidebarOpen = false; partnersOpen = false"
                >Gerenciar</a>
                <a
                    href="{{ route('admin.partner-categories.index') }}"
                    class="block rounded-md px-2 py-1.5 text-sm hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.partner-categories.*') ? 'bg-white/10 text-white font-medium' : 'text-white/90' }}"
                    @click="sidebarOpen = false; partnersOpen = false"
                >Categorias</a>
                <a
                    href="{{ route('admin.tipos-documento-empresa.index') }}"
                    class="block rounded-md px-2 py-1.5 text-sm hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.tipos-documento-empresa.*') ? 'bg-white/10 text-white font-medium' : 'text-white/90' }}"
                    @click="sidebarOpen = false; partnersOpen = false"
                >Tipos doc. empresa</a>
                <a
                    href="{{ route('admin.tipos-documento-funcionario.index') }}"
                    class="block rounded-md px-2 py-1.5 text-sm hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.tipos-documento-funcionario.*') ? 'bg-white/10 text-white font-medium' : 'text-white/90' }}"
                    @click="sidebarOpen = false; partnersOpen = false"
                >Tipos doc. funcionário</a>
                <a
                    href="{{ route('admin.funcoes-funcionario.index') }}"
                    class="block rounded-md px-2 py-1.5 text-sm hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.funcoes-funcionario.*') ? 'bg-white/10 text-white font-medium' : 'text-white/90' }}"
                    @click="sidebarOpen = false; partnersOpen = false"
                >Funções</a>
            </div>
        </div>
    </nav>

    @include('layouts.sidebar._footer')
</div>
