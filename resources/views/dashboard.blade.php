<x-app-layout>
    <x-slot name="header" class="text-gold-500">
        <h2 class="mt-8 -mb-4 text-8xl font-bold leading-tight text-center cinzel text-gold-500">
            {{ __("let's make some money") }}
        </h2>
    </x-slot>

    <div class="py-12 bg-black">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden text-lg font-bold text-center border-y-2 border-white cardo-bold">
                    <p class="p-6 text-gold-500">
                        Before you can join the game, the man with the golden suit must approve your account.
                    </p>
                    <p class="px-6 text-gold-100">Find the man with the golden suit.</p>
                    <p class="p-6 text-gold-500">
                        He will not approve you unless your account name matches the name on your Laracon badge. If you need to change your name to match your badge, do that below.
                    </p>
            </div>
        </div>
    </div>
</x-app-layout>
