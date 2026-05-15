@props(['modalName', 'action', 'docNome', 'validade' => null, 'hasFile' => false])

<x-modal :name="$modalName" focusable>
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="p-6 space-y-5">
        @csrf

        <div>
            <h2 class="text-lg font-semibold text-gray-900">
                {{ $hasFile ? 'Substituir documento' : 'Enviar documento' }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                <span class="font-medium text-gray-700">{{ $docNome }}</span>
            </p>
        </div>

        {{-- Drop-zone --}}
        <div
            x-data="{ dragging: false, fileName: '' }"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; fileName = $event.dataTransfer.files[0]?.name ?? ''"
            :class="dragging ? 'border-marino bg-marino/5' : 'border-gray-300 hover:border-gray-400'"
            class="relative flex flex-col items-center justify-center rounded-lg border-2 border-dashed px-6 py-8 text-center transition cursor-pointer"
            @click="$refs.fileInput.click()"
        >
            <svg class="mx-auto h-10 w-10 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <p class="mt-2 text-sm font-medium text-gray-700" x-text="fileName || 'Clique ou arraste o arquivo aqui'"></p>
            <p class="text-xs text-gray-400">PDF, JPG, JPEG ou PNG</p>
            <input
                x-ref="fileInput"
                type="file"
                name="arquivo"
                accept=".pdf,.jpg,.jpeg,.png"
                class="sr-only"
                required
                @change="fileName = $event.target.files[0]?.name ?? ''"
            >
        </div>

        {{-- Validade --}}
        <div>
            <label for="validade-{{ $modalName }}" class="block text-sm font-medium text-gray-700">
                Validade do documento
            </label>
            <input
                id="validade-{{ $modalName }}"
                type="date"
                name="validade"
                value="{{ $validade?->format('Y-m-d') }}"
                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-marino focus:ring-marino"
                required
            >
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
            <x-primary-button>Enviar</x-primary-button>
        </div>
    </form>
</x-modal>
