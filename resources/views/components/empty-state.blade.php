@props(['title', 'description' => null])

<div class="flex flex-col items-center justify-center py-16 text-center">
    @if (isset($icon))
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-zinc-100 text-zinc-400 ring-2 ring-zinc-100/60">
            {{ $icon }}
        </div>
    @else
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-zinc-100 text-zinc-400 ring-2 ring-zinc-100/60">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
            </svg>
        </div>
    @endif

    <p class="mt-4 text-base font-semibold text-zinc-800">{{ $title }}</p>

    @if ($description)
        <p class="mt-1.5 text-sm text-zinc-500">{{ $description }}</p>
    @endif

    @if (isset($actions))
        <div class="mt-5">{{ $actions }}</div>
    @endif
</div>
