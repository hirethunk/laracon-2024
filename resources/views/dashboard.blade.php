<x-app-layout>
    <x-slot name="header" class="">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __("Let's make some money") }}
        </h2>
    </x-slot>

    @if(Auth::user()->player)
        <livewire:voting-card />
    @else
        <div class="py-12 bg-black">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-white border-amber-400 border-8 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        Before you can join the game, the man with the golden suit must approve your account. Find the man with the golden suit.
                    </div>
                    <div class="p-6">
                        He will not approve you unless your account name matches the name on your Laracon badge. If you need to change your name to match your badge, do that below.
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
