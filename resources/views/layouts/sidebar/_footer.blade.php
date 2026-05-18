<div class="shrink-0 border-t border-white/[0.08] px-4 py-3">
    <div class="flex items-center gap-2.5">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-bold text-white">
            {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
        </span>
        <div class="min-w-0 flex-1">
            <p class="truncate text-xs font-semibold text-white">{{ Auth::user()->name }}</p>
            <p class="truncate text-[10px] text-white/45">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <div class="mt-3 flex gap-1">
        <a href="{{ route('perfil.edit') }}" class="flex-1 rounded-lg px-2.5 py-1.5 text-center text-xs text-white/70 hover:bg-white/[0.08] hover:text-white transition" @click="sidebarOpen = false; partnersOpen = false">
            Perfil
        </a>
        <form method="POST" action="{{ route('logout') }}" class="flex-1">
            @csrf
            <button type="submit" class="w-full rounded-lg px-2.5 py-1.5 text-xs text-white/70 hover:bg-red-500/20 hover:text-red-300 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/40">
                Sair
            </button>
        </form>
    </div>
</div>
