<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-center text-white leading-tight cinzel lowercase">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="space-y-12">
        <x-form.card>
            <livewire:user-profile />
        </x-form.card>

        <x-form.card>
            @include('profile.partials.update-password-form')
        </x-form.card>

        <x-form.card>
            @include('profile.partials.delete-user-form')
        </x-form.card>
    </div>
</x-app-layout>
