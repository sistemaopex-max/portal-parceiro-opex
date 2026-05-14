<div class="shrink-0 border-t border-white/15 bg-black/20 p-3">
    <div class="mb-2 truncate text-xs font-medium text-white/60">{{ Auth::user()->email }}</div>
    <div class="flex flex-col gap-1">
        <a href="{{ route('profile.edit') }}" class="rounded-md px-2 py-1.5 text-sm text-white hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40" @click="sidebarOpen = false; partnersOpen = false">
            Perfil
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full rounded-md px-2 py-1.5 text-left text-sm text-white hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                Sair
            </button>
        </form>
    </div>
</div>
