<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorias de parceiro
            </h2>
            <a href="{{ route('admin.partner-categories.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nova categoria
            </a>
        </div>
    </x-slot>

    <div class="space-y-4">
            @if (session('status'))
                <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('delete'))
                <div class="p-4 bg-red-50 text-red-800 rounded-md text-sm font-medium">
                    {{ $errors->first('delete') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($categories->isEmpty())
                        <p class="text-gray-600">Nenhuma categoria cadastrada ainda.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr class="text-left text-gray-600">
                                        <th class="py-2 pe-4">Nome</th>
                                        <th class="py-2 pe-4">Slug</th>
                                        <th class="py-2 pe-4">Ativa</th>
                                        <th class="py-2 pe-4 text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td class="py-3 pe-4 font-medium text-gray-900">{{ $category->name }}</td>
                                            <td class="py-3 pe-4 font-mono text-gray-700">{{ $category->slug }}</td>
                                            <td class="py-3 pe-4">
                                                @if ($category->is_active)
                                                    <span class="text-green-700">Sim</span>
                                                @else
                                                    <span class="text-gray-500">Não</span>
                                                @endif
                                            </td>
                                            <td class="py-3 pe-4 text-end whitespace-nowrap">
                                                <a href="{{ route('admin.partner-categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900 me-3">Editar</a>
                                                <form action="{{ route('admin.partner-categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta categoria?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">Excluir</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
    </div>
</x-app-layout>
