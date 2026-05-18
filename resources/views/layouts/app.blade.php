<x-base-layout>
    <x-slot name="sidebar">
        @auth
            @if (Auth::user()->isBackOffice())
                @include('layouts.sidebar.admin')
            @else
                @include('layouts.sidebar.parceiro')
            @endif
        @endauth
    </x-slot>

    <x-slot name="content">
        <div class="flex min-h-screen min-w-0 flex-1 flex-col">

            {{-- Topbar --}}
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between gap-4 border-b border-zinc-200/70 bg-white/95 px-4 backdrop-blur supports-[backdrop-filter]:bg-white/85 sm:px-6">

                {{-- Esquerda: hambúrguer (mobile) + breadcrumb (desktop) --}}
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex shrink-0 rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:ring-2 focus:ring-marino lg:hidden"
                        @click="sidebarOpen = true"
                        aria-label="Abrir menu"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <x-breadcrumbs :items="$breadcrumbs ?? []" />
                </div>

                {{-- Direita: user menu --}}
                @auth
                    <div class="flex shrink-0 items-center gap-2" x-data="{ userOpen: false }" @click.outside="userOpen = false">
                        <button
                            type="button"
                            @click="userOpen = !userOpen"
                            class="flex items-center gap-2.5 rounded-xl px-2.5 py-2 text-sm text-zinc-700 hover:bg-zinc-100 transition focus:outline-none focus:ring-2 focus:ring-marino"
                        >
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-marino to-marino-700 text-xs font-bold text-white shadow-sm">
                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:block max-w-[9rem] truncate text-sm font-semibold text-zinc-800">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-zinc-400 transition-transform duration-150" :style="{ transform: userOpen ? 'rotate(180deg)' : 'rotate(0)' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div
                            x-show="userOpen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-4 top-[4.25rem] z-50 w-52 origin-top-right rounded-2xl border border-zinc-200 bg-white py-1.5 shadow-card-md"
                        >
                            <div class="border-b border-zinc-100 px-4 py-2.5">
                                <p class="text-xs font-semibold text-zinc-800">{{ Auth::user()->name }}</p>
                                <p class="mt-0.5 truncate text-[11px] text-zinc-400">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('perfil.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50">
                                <svg class="h-4 w-4 shrink-0 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span>Perfil</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                                    </svg>
                                    <span>Sair</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </header>

            @isset($header)
                <div class="border-b border-zinc-200/70 bg-white">
                    <div class="mx-auto max-w-[1600px] px-4 py-5 sm:px-6 lg:px-8 xl:px-10">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <main class="min-h-0 flex-1 bg-zinc-50">
                <div class="mx-auto max-w-[1600px] px-4 py-7 sm:px-6 lg:px-8 lg:py-8 xl:px-10">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </x-slot>
</x-base-layout>
