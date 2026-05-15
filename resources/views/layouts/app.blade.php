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
        <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:min-h-0">

            {{-- Topbar --}}
            <header class="sticky top-0 z-30 flex h-14 shrink-0 items-center justify-between gap-3 border-b border-gray-200 bg-white/95 px-4 backdrop-blur supports-[backdrop-filter]:bg-white/80">
                {{-- Hamburguer (mobile) --}}
                <button
                    type="button"
                    class="inline-flex rounded-md p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-marino lg:hidden"
                    @click="sidebarOpen = true"
                    aria-label="Abrir menu"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                {{-- User menu (desktop) --}}
                @auth
                    <div class="ms-auto flex items-center gap-2" x-data="{ userOpen: false }" @click.outside="userOpen = false">
                        <button
                            type="button"
                            @click="userOpen = !userOpen"
                            class="flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-sm text-gray-700 hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-marino"
                        >
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-marino text-xs font-bold text-white">
                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:block max-w-[10rem] truncate font-medium">{{ Auth::user()->name }}</span>
                            <svg class="h-3.5 w-3.5 text-gray-400 transition-transform duration-150" :style="{ transform: userOpen ? 'rotate(180deg)' : 'rotate(0)' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
                            class="absolute right-4 top-12 z-50 w-48 origin-top-right rounded-lg border border-gray-200 bg-white py-1 shadow-lg"
                        >
                            <div class="border-b border-gray-100 px-4 py-2">
                                <p class="truncate text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                                    </svg>
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </header>

            @isset($header)
                <div class="border-b border-gray-200 bg-white">
                    <div class="mx-auto max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8 lg:py-5 xl:px-10 2xl:px-12">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <main class="min-h-0 flex-1 bg-slate-50">
                <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8 xl:px-10 2xl:px-12 2xl:py-10">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </x-slot>
</x-base-layout>
