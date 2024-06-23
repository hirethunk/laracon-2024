<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-white leading-tight cinzel lowercase">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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
    </div>
</x-app-layout>
