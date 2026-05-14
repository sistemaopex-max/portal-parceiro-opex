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
            <header class="sticky top-0 z-30 flex h-14 shrink-0 items-center gap-3 border-b border-gray-200 bg-white/95 px-4 backdrop-blur supports-[backdrop-filter]:bg-white/80 lg:hidden">
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
    </x-slot>
</x-base-layout>
