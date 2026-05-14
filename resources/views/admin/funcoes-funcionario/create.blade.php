<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Nova função</h2></x-slot>
    <div class="max-w-2xl">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route('admin.funcoes-funcionario.store') }}">@csrf @include('admin.funcoes-funcionario._form', ['funcao' => null, 'tipos' => $tipos])</form>
        </div>
    </div>
</x-app-layout>
