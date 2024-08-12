<x-app-layout>
    <x-slot name="header" class="text-gold-500">
        <h2 class="py-4 font-bold leading-tight text-center text-5xl font-serif text-gold-500">
            {{ __("let's make some money") }}
        </h2>
    </x-slot>

    <div class="bg-black">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col py-6 overflow-hidden text-lg font-bold text-center border-white border-y-2 font-sans gap-y-6">
                <p class="text-gold-100">
                    Find the man with the golden briefcase. He will admit you into the game.
                </p>
                <p>
                    <span class="text-white">
                        Your account name must match the name on your Laracon badge.
                        You can change your name on your
                    </span>
                    <a href="/profile" class="text-indigo-400">Profile Page</a><span>.</span>
                </p>
            </div>
        </div>
    </div>
    <x-card>
        <p class="text-white">
            Before you join, you may add a referrer. Select an active player. When you join the game, you will receive an extra upvote, and so will they.
        </p>
    </x-card>
    <x-live-feed />
</x-app-layout>
