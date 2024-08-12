<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-center text-white leading-tight font-serif lowercase">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="space-y-4">
        <x-card>
            @if(auth()->user()->currentPlayer())
                <p class="text-gold-500">The game is afoot, and you can no longer change your name.</p>
            @else
                <livewire:user-profile />
            @endif
        </x-card>

        <x-card>
            @include('profile.partials.update-password-form')
        </x-card>

        <x-card>
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
</x-app-layout>
