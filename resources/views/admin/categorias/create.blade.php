<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nova categoria de parceiro
            </h2>
            <a href="{{ route('admin.categorias.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Voltar à lista</a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        @include('admin.categorias._category-alerts')

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route('admin.categorias.store') }}">
                @csrf
                @include('admin.categorias._form', ['category' => null])
            </form>
        </div>
    </div>
</x-app-layout>
