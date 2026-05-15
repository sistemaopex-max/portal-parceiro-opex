@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'h-10 w-full rounded-lg border-zinc-300 bg-white text-sm text-zinc-900 shadow-sm placeholder-zinc-400 focus:border-marino focus:ring-marino transition']) }}>
