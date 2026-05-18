<x-app-layout>
    <x-slot name="header">
        <x-page-heading title="Meu perfil" subtitle="Gerencie seus dados de acesso" />
    </x-slot>

    <div class="mx-auto max-w-2xl space-y-6">
        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-card sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-card sm:p-8">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</x-app-layout>
