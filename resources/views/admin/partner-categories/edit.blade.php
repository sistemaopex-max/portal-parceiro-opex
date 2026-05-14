<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar categoria
        </h2>
    </x-slot>

    <div class="max-w-2xl">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="POST" action="{{ route('admin.partner-categories.update', $category) }}">
                    @csrf
                    @method('PUT')
                    @include('admin.partner-categories._form', ['category' => $category, 'tiposDocumentoEmpresa' => $tiposDocumentoEmpresa])
                </form>
            </div>
    </div>
</x-app-layout>
