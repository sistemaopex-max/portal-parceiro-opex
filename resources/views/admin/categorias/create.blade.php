<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Nova categoria de parceiro"
            :back="route('admin.categorias.index')"
            back-label="Categorias"
        />
    </x-slot>

    <div class="max-w-2xl space-y-6">
        @include('admin.categorias._category-alerts')

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.categorias.store') }}">
                @csrf
                @include('admin.categorias._form', ['category' => null])
            </form>
        </div>
    </div>
</x-app-layout>
