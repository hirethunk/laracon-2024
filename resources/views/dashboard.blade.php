<x-app-layout>
    <x-slot name="header" class="text-gold-500">
        <h2 class="font-bold leading-tight text-center text-8xl cinzel text-gold-500">
            {{ __("let's make some money") }}
        </h2>
    </x-slot>

    <div class="py-12 bg-black">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col py-6 overflow-hidden text-lg font-bold text-center border-white border-y-2 cardo-bold gap-y-6">
                <p class="text-gold-100">
                    Before you can join the game, the man with the golden suit must approve your account.
                </p>
                <p class="text-gold-100">
                    Find the man with the golden suit.
                </p>
                <p>
                    <span class="text-gold-100">
                        He will not approve you unless your account name matches the name on your Laracon badge.
                        You may change your name on the
                    </span>
                    <a href="/profile" class="text-indigo-400">Profile Page</a><span>.</span>
                </p>
            </div>
        </div>
    </div>
    <x-live-feed />
</x-app-layout>
