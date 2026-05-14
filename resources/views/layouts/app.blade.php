<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div
            class="flex min-h-screen flex-col bg-slate-50 text-gray-900 lg:flex-row"
            x-data="{ sidebarOpen: false }"
            @keydown.window.escape="sidebarOpen = false"
        >
            {{-- Mobile / tablet: overlay --}}
            <div
                x-cloak
                x-show="sidebarOpen"
                x-transition.opacity.duration.200ms
                class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-[1px] lg:hidden"
                aria-hidden="true"
                @click="sidebarOpen = false"
            ></div>

            {{-- Sidebar --}}
            <aside
                class="fixed inset-y-0 left-0 z-50 flex w-[min(17.5rem,88vw)] -translate-x-full flex-col border-r border-marino-dark bg-marino shadow-xl transition-transform duration-200 ease-out lg:static lg:z-0 lg:w-64 lg:max-w-none lg:translate-x-0 lg:shrink-0 lg:shadow-none xl:w-72 2xl:w-72"
                :class="{ 'translate-x-0': sidebarOpen }"
                aria-label="Navegação lateral"
            >
                @include('layouts.sidebar')
            </aside>

            {{-- Conteúdo principal --}}
            <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:min-h-0">
                {{-- Barra superior compacta (mobile / notebook pequeno) --}}
                <header class="sticky top-0 z-30 flex h-14 shrink-0 items-center justify-between gap-3 border-b border-gray-200 bg-white/95 px-4 backdrop-blur supports-[backdrop-filter]:bg-white/80 lg:px-6 xl:h-16 xl:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            class="inline-flex rounded-md p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 lg:hidden"
                            @click="sidebarOpen = true"
                            aria-label="Abrir menu"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                    <div class="hidden shrink-0 items-center gap-2 text-sm text-gray-600 lg:flex">
                        <span class="max-w-[14rem] truncate xl:max-w-xs 2xl:max-w-md">{{ Auth::user()->name }}</span>
                    </div>
                </header>

                @isset($header)
                    <div class="border-b border-gray-200 bg-white">
                        <div class="mx-auto max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8 lg:py-5 xl:px-10 2xl:px-12">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <main class="min-h-0 flex-1">
                    <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8 xl:px-10 2xl:px-12 2xl:py-10">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
