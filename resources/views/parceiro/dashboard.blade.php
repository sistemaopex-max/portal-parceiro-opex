<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Painel do parceiro
        </h2>
    </x-slot>

    <div class="space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <p class="text-lg font-medium">Olá, {{ auth()->user()->name }}.</p>
                    <p class="text-gray-600">Você está no portal do parceiro.</p>
                    <p class="text-sm text-gray-500">
                        Papel:
                        <span class="font-mono font-semibold text-gray-800">{{ auth()->user()->role }}</span>
                    </p>
                </div>
            </div>

            @if (auth()->user()->partner)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-1">
                        <h3 class="font-semibold text-gray-800">Empresa</h3>
                        <p><span class="text-gray-600">Razão social:</span> {{ auth()->user()->partner->legal_name }}</p>
                        @if (auth()->user()->partner->trade_name)
                            <p><span class="text-gray-600">Fantasia:</span> {{ auth()->user()->partner->trade_name }}</p>
                        @endif
                        <p><span class="text-gray-600">Categoria:</span> {{ auth()->user()->partner->category->name }}</p>
                    </div>
                </div>
            @endif
    </div>
</x-app-layout>
