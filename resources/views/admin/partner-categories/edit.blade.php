<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar categoria: {{ $category->nome }}
            </h2>
            <a href="{{ route('admin.partner-categories.show', $category) }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Voltar</a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        @include('admin.partner-categories._category-alerts')

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route('admin.partner-categories.update', $category) }}">
                @csrf
                @method('PUT')
                @include('admin.partner-categories._form-edit', ['category' => $category])
            </form>
        </div>
    </div>
</x-app-layout>
