@php
    $currentPartner = auth()->user()?->currentPartner();
    $allFiliais = auth()->user()?->partners()->ativos()->orderBy('razao_social')->get() ?? collect();
    $temVariasFiliais = $allFiliais->count() > 1;
@endphp

@if ($currentPartner)
    <div @class(['shrink-0 px-4 py-3'])>
        <p class="mb-2 px-0.5 text-[10px] font-medium uppercase tracking-wider text-white/40">
            Filial selecionada
        </p>

        @if ($temVariasFiliais)
            <div
                class="flex flex-col-reverse gap-1"
                x-data="{ filiaisOpen: false }"
                @click.outside="filiaisOpen = false"
            >
                <button
                    type="button"
                    @click="filiaisOpen = !filiaisOpen"
                    class="flex w-full items-start justify-between gap-2 rounded-lg px-0.5 py-1 text-left transition hover:opacity-90 focus:outline-none"
                    :aria-expanded="filiaisOpen.toString()"
                    aria-label="Selecionar outra filial"
                >
                    <div class="min-w-0 flex-1">
                        <p class="text-[13px] font-semibold leading-snug text-white">
                            {{ $currentPartner->razao_social }}
                        </p>
                        @if ($currentPartner->local_filial)
                            <p class="filial-option-local mt-1 text-xs leading-relaxed">
                                {{ $currentPartner->local_filial }}
                            </p>
                        @endif
                    </div>
                    <svg
                        class="mt-0.5 h-4 w-4 shrink-0 rotate-180 text-white/70 transition-transform duration-200"
                        :class="{ '!rotate-0': filiaisOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div
                    x-show="filiaisOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-cloak
                    class="filial-selector-panel max-h-56 space-y-0.5 overflow-y-auto rounded-xl bg-marino-600/95 px-2 py-2 shadow-xl ring-1 ring-inset ring-white/[0.08] backdrop-blur-sm"
                >
                    @foreach ($allFiliais as $filial)
                        @if ($filial->id !== $currentPartner->id)
                            <form method="POST" action="{{ route('parceiro.filiais.switch', $filial) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="filial-option-btn w-full rounded-lg px-2.5 py-2 text-left transition hover:bg-white/[0.10] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/30"
                                    @click="sidebarOpen = false"
                                >
                                    <span class="filial-option-nome block truncate text-[13px] font-semibold">{{ $filial->razao_social }}</span>
                                    @if ($filial->local_filial)
                                        <span class="filial-option-local mt-0.5 block truncate text-xs">{{ $filial->local_filial }}</span>
                                    @endif
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            <div class="px-0.5 py-1">
                <p class="text-[13px] font-semibold leading-snug text-white">
                    {{ $currentPartner->razao_social }}
                </p>
                @if ($currentPartner->local_filial)
                    <p class="filial-option-local mt-1 text-xs leading-relaxed">
                        {{ $currentPartner->local_filial }}
                    </p>
                @endif
            </div>
        @endif
    </div>
@endif
