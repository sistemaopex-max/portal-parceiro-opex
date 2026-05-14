<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Painel administrativo
        </h2>
    </x-slot>

    <div class="space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <p class="text-lg font-medium">Olá, {{ auth()->user()->name }}.</p>
                    <p class="text-gray-600">Área restrita a administradores.</p>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('admin.partner-categories.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                        Gerenciar categorias de parceiro
                    </a>
                </div>
            </div>
    </div>
</x-app-layout>
