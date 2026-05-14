<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nova categoria de parceiro
        </h2>
    </x-slot>

    <div class="max-w-2xl">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="POST" action="{{ route('admin.partner-categories.store') }}">
                    @csrf
                    @include('admin.partner-categories._form', ['category' => null, 'tiposDocumentoEmpresa' => $tiposDocumentoEmpresa])
                </form>
            </div>
    </div>
</x-app-layout>
