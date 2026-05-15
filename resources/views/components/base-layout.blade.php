@props([])

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
            x-data="{ sidebarOpen: false, partnersOpen: false }"
            @if (request()->routeIs('admin.parceiros.*', 'admin.partner-categories.*', 'admin.documentos-empresa.*', 'admin.documentos-funcionario.*', 'admin.invitations.*') && auth()->check() && auth()->user()->isBackOffice())
                x-init="partnersOpen = true"
            @endif
            @keydown.window.escape="sidebarOpen = false; partnersOpen = false"
            @sidebar-close.window="sidebarOpen = false"
        >
            {{-- Mobile / tablet: overlay --}}
            <div
                x-cloak
                x-show="sidebarOpen"
                x-transition.opacity.duration.200ms
                class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-[1px] lg:hidden"
                aria-hidden="true"
                @click="sidebarOpen = false; partnersOpen = false"
            ></div>

            {{-- Sidebar (links vêm do slot) --}}
            <aside
                class="fixed inset-y-0 left-0 z-50 flex w-[min(17.5rem,88vw)] -translate-x-full flex-col border-r border-marino-dark bg-marino shadow-xl transition-transform duration-200 ease-out lg:static lg:z-0 lg:w-64 lg:max-w-none lg:translate-x-0 lg:shrink-0 lg:shadow-none xl:w-72 2xl:w-72"
                :class="{ 'translate-x-0': sidebarOpen }"
                aria-label="Navegação lateral"
            >
                {{ $sidebar }}
            </aside>

            {{-- Conteúdo principal (slot) --}}
            {{ $content }}
        </div>
    </body>
</html>
