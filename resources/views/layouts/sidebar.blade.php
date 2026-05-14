@php
    $homeUrl = Auth::user()->isPartner() ? route('parceiro.dashboard') : route('admin.dashboard');
@endphp

<div class="flex h-full min-h-0 flex-col text-white">
    <div class="flex shrink-0 items-center justify-center border-b border-white/15 px-4 py-3">
        <a href="{{ $homeUrl }}" class="flex w-full items-center justify-center rounded-lg outline-none focus-visible:ring-2 focus-visible:ring-white/50" @click="sidebarOpen = false">
            <x-application-logo class="my-4 h-7 w-auto max-w-[63%]" />
        </a>
    </div>

    <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain p-3" aria-label="Menu principal">
        <x-sidebar-link :href="$homeUrl" :active="request()->routeIs('admin.dashboard', 'parceiro.dashboard')" @click="sidebarOpen = false">
            <svg class="h-5 w-5 shrink-0 text-white/55 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Início</span>
        </x-sidebar-link>

        @if (Auth::user()->isBackOffice())
            <x-sidebar-link :href="route('admin.partner-categories.index')" :active="request()->routeIs('admin.partner-categories.*')" @click="sidebarOpen = false">
                <svg class="h-5 w-5 shrink-0 text-white/55 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
                </svg>
                <span>Categorias</span>
            </x-sidebar-link>
        @endif
    </nav>

    <div class="shrink-0 border-t border-white/15 bg-black/20 p-3">
        <div class="mb-2 truncate text-xs font-medium text-white/60">{{ Auth::user()->email }}</div>
        <div class="flex flex-col gap-1">
            <a href="{{ route('profile.edit') }}" class="rounded-md px-2 py-1.5 text-sm text-white hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40" @click="sidebarOpen = false">
                Perfil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-md px-2 py-1.5 text-left text-sm text-white hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                    Sair
                </button>
            </form>
        </div>
    </div>
</div>
