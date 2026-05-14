<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar parceiro
        </h2>
    </x-slot>

    <div class="max-w-2xl">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route('admin.parceiros.update', $partner) }}">
                @csrf
                @method('PUT')
                @include('admin.partners._form', ['partner' => $partner, 'categories' => $categories])
            </form>
        </div>
    </div>
</x-app-layout>
