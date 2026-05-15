<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo convite de cadastro</h2>
    </x-slot>

    <div class="space-y-4">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <a href="{{ route('admin.invitations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Convites</a>

            <form method="POST" action="{{ route('admin.invitations.store') }}" class="space-y-5 max-w-lg">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail do prestador <span class="text-red-500">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('email') border-red-300 @enderror"
                        placeholder="email@empresa.com"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoria <span class="text-red-500">*</span></label>
                    <select
                        id="categoria_id"
                        name="categoria_id"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('categoria_id') border-red-300 @enderror"
                    >
                        <option value="">Selecione uma categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('categoria_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <x-config-action-btn type="submit" variant="primary">Enviar convite</x-config-action-btn>
                    <x-config-action-btn :href="route('admin.invitations.index')">Cancelar</x-config-action-btn>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
