<section>
    <header>
        <h2 class="text-lg font-semibold text-zinc-900">
            Dados pessoais
        </h2>

        <p class="mt-1 text-sm text-zinc-500">
            Atualize seu nome de exibição no portal.
        </p>
    </header>

    <form method="post" action="{{ route('perfil.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >Salvo.</p>
            @endif
        </div>
    </form>
</section>
