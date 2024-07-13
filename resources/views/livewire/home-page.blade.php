<div>
    <x-slot name="header" class="text-gold-500">
        <h2 class="py-4 font-bold leading-tight text-center text-5xl cinzel text-gold-500">
            {{ __("let's make some money") }}
        </h2>
    </x-slot>

    <div class="bg-black">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col py-6 overflow-hidden text-lg text-center border-white border-t-2 cardo-bold gap-y-6">
                <p class="text-gold-100 font-normal">
                    Find the man with the golden briefcase. He will admit you into the game.
                </p>
                <p>
                    <span class="text-white font-normal">
                        Your account name must match the name on your Laracon badge.
                        You can change your name on your
                    </span>
                    <a href="/profile" class="text-indigo-400">Profile Page</a><span>.</span>
                </p>
            </div>
        </div>
    </div>
    <x-card>
        @if($this->referrer)
            <p class="text-white">
                Referred by <span class="text-gold-100">{{ $this->referrer->user->name }}.</span>
                When you join the game, you will receive an extra upvote, and so will they.
            </p>
        @else
            <p class="text-white pb-4">
                Before you join, you may add a referrer. Select an active player. When you join the game, you will receive an extra upvote, and so will they.
            </p>

            <x-form.select
                label="Referrer"
                name="referrer_id"
                custom="w-6/12"
                wire:model="referrer_id"
                :options="$this->players->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
                selected="Choose a Player"
            />
            <div class="flex justify-between items-center mt-4">
                <x-primary-button wire:click="addReferrer" wire:loading.attr="disabled" color="gold">
                    Add Referrer
                </x-primary-button>
            </div>
        @endif
    </x-card>
    <x-live-feed />
</div>
