<x-app-layout>
    <x-slot name="header">
        <x-page-heading
            title="Editar parceiro"
            :back="route('admin.parceiros.show', $partner)"
            back-label="Voltar"
        />
    </x-slot>

    <div class="max-w-2xl">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.parceiros.update', $partner) }}">
                @csrf
                @method('PUT')
                @include('admin.partners._form', ['partner' => $partner, 'categories' => $categories])
            </form>
        </div>
    </div>
</x-app-layout>
