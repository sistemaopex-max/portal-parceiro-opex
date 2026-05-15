@props([])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div
            class="flex min-h-screen flex-col bg-zinc-50 text-gray-900 lg:flex-row"
            x-data="{ sidebarOpen: false, partnersOpen: false }"
            @if (request()->routeIs('admin.parceiros.*', 'admin.categorias.*', 'admin.docs.*', 'admin.docs-func.*', 'admin.invitations.*') && auth()->check() && auth()->user()->isBackOffice())
                x-init="partnersOpen = true"
            @endif
            @keydown.window.escape="sidebarOpen = false; partnersOpen = false"
            @sidebar-close.window="sidebarOpen = false"
        >
            {{-- Mobile overlay --}}
            <div
                x-cloak
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-zinc-900/60 backdrop-blur-[2px] lg:hidden"
                aria-hidden="true"
                @click="sidebarOpen = false; partnersOpen = false"
            ></div>

            {{-- Sidebar --}}
            <aside
                class="sidebar-gradient fixed inset-y-0 left-0 z-50 flex w-[260px] -translate-x-full flex-col shadow-2xl transition-transform duration-200 ease-out lg:static lg:z-0 lg:translate-x-0 lg:shrink-0 lg:shadow-none"
                :class="{ 'translate-x-0': sidebarOpen }"
                aria-label="Navegação lateral"
            >
                {{ $sidebar }}
            </aside>

            {{-- Conteúdo principal --}}
            {{ $content }}
        </div>
    </body>
</html>
