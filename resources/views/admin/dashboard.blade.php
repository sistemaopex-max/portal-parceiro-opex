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
        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('admin.partner-categories.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:ring-2 hover:ring-indigo-500 transition">
                <h3 class="text-lg font-semibold text-gray-900">Categorias</h3>
                <p class="mt-2 text-sm text-gray-600">Cadastre categorias, documentos da empresa, funções e documentos por função.</p>
            </a>
            <a href="{{ route('admin.parceiros.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:ring-2 hover:ring-indigo-500 transition">
                <h3 class="text-lg font-semibold text-gray-900">Parceiros</h3>
                <p class="mt-2 text-sm text-gray-600">Gerencie empresas parceiras e valide documentos enviados.</p>
            </a>
        </div>
    </div>
</x-app-layout>
