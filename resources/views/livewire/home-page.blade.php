<div class="space-y-4" wire:poll.5000ms="isApproved">
    <x-slot name="header" class="text-gold-500">
        <h2 class="text-5xl font-bold leading-tight text-center sm:text-6xl md:text-7xl font-serif text-gold-500">
            {{ __("let's make some money") }}
        </h2>
    </x-slot>

    <div class="flex flex-col py-6 mx-auto overflow-hidden text-lg font-normal text-center text-white border-t-2 border-white gap-y-6">
        <p>
            Find the 
            <button class="text-link">
                <a href="https://x.com/johnrudolphdrex" target="_blank">
                    man
                </a>
            </button>
            
            with the <span class="text-gold-500">plastic bag full of $1,500</span>. He will admit you into the game.
        </p>
        <p>
            Your account name must match the name on your Laracon badge.
            You can change your name on your
            <a href="/profile" class="text-link">Profile Page</a>.
        </p>
    </div>
    <x-card>
        @if($this->referrer)
            <p class="text-white">
                Referred by <span class="text-gold-500">{{ $this->referrer->user->name }}.</span>
                When you join the game, you will receive an extra upvote, and so will they.
            </p>
        @else
            <p class="pb-4 text-sm text-neutral-300">
                Before you join, you may add a referrer. Select an active player. When you join the game, you will receive an extra upvote, and so will they.
            </p>

            <x-form.autocomplete
                label="referrer"
                selected="referrer_id"
                search="search"
                :options="$this->options"
            />

            <div class="flex items-center justify-between mt-4">
                <x-primary-button wire:click="addReferrer" wire:loading.attr="disabled">
                    Add Referrer
                </x-primary-button>
            </div>
        @endif
    </x-card>
    <x-live-feed />
</div>
