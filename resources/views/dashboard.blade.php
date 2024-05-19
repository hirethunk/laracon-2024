<x-app-layout>
    <x-slot name="header" class="">
        <h2 class="mt-8 -mb-4 text-2xl font-bold leading-tight text-center text-gold-500 cinzel">
            {{ __("Let's make some Money") }}
        </h2>
    </x-slot>

    @if(Auth::user()->player)
        <livewire:voting-card />
    @else
        <div class="py-12 bg-black">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden text-lg font-bold text-center border-8 shadow-sm cardo-bold text-gray-50 border-gold-100 sm:rounded-lg">
                    <div class="m-1 border-2 border-white">
                        <p class="p-6">
                            Before you can join the game, the man with the golden suit must approve your account.
                        </p>
                        <p class="px-6">Find the man with the golden suit.</p>
                        <p class="p-6">
                            He will not approve you unless your account name matches the name on your Laracon badge. If you need to change your name to match your badge, do that below.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-20 h-10 w-1/2 bg-black flex justify-between items-center">
            <span class="p-8 border-gold-100 text-white">Border Gold 100</span>
            <span class="text-gold-100">Text Gold 100</span>
            <div class="p-10 bg-gold-100 text-black">BG Gold 100</div>
        </div>

        <div class="p-20 h-10 w-1/2 bg-black flex justify-between items-center">
            <span class="p-8 border-gold-500 text-white">Border Gold 500</span>
            <span class="text-gold-500">Text Gold 500</span>
            <div class="p-10 bg-gold-500 text-black">BG Gold 500</div>
        </div>

        <div class="p-20 h-10 w-1/2 bg-black flex justify-between items-center">
            <span class="p-8 border-gold-900 text-white">Border Gold 900</span>
            <span class="text-gold-900">Text Gold 900</span>
            <div class="p-10 bg-gold-900 text-black">BG Gold 900</div>
        </div>
    @endif
</x-app-layout>
