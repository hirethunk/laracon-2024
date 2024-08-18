<div class="space-y-4">
    <x-slot name="header" class="text-gold-500">
        <h2 class="text-5xl font-bold leading-tight text-center sm:text-6xl md:text-7xl font-serif text-gold-500">
            {{ __("let's make some money") }}
        </h2>
    </x-slot>

    <div class="flex flex-col py-6 mx-auto overflow-hidden text-lg font-normal text-center text-white border-t-2 border-white gap-y-6">
        <p>
            Find the man with the <span class="text-gold-100">golden briefcase</span>. He will admit you into the game.
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
                Referred by <span class="text-gold-100">{{ $this->referrer->user->name }}.</span>
                When you join the game, you will receive an extra upvote, and so will they.
            </p>
        @else
            <p class="pb-4 text-sm text-neutral-300">
                Before you join, you may add a referrer. Select an active player. When you join the game, you will receive an extra upvote, and so will they.
            </p>
        {{--
        <x-form.select
            label="Referrer"
            name="referrer_id"
            wire:model="referrer_id"
            :options="$this->players->mapWithKeys(fn($p) => [$p->id => $p->user->name])"
            selected="Choose a Player"
        />
        --}}
        <x-form.label customStyle="text-sm text-neutral-200 font-medium" name="Referrer" label="Referrer" />

        <x-autocomplete wire:model.live="referrer_id">
            <x-autocomplete.input
                wire:model.live="name"
                class="px-2 w-full bg-neutral-900 border-2 rounded-lg">

                @if ($referrer_id)
                    <button type="button" x-on:click="clear()" class="absolute right-0 mr-1">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                @else
                    <button type="button" x-on:click="toggle" class="absolute right-0 mr-1" tabindex="-1">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif
            </x-autocomplete.input>
            <x-autocomplete.list class="absolute bg-black rounded-lg w-full px-2 mt-1 max-h-64" x-cloak>
                @foreach ($this->options as $id => $name)
                    <x-autocomplete.item :id="$id" :value="$name">
                        <span>{{ $name }}</span>
                    </x-autocomplete.item>
                @endforeach
            </x-autocomplete.list>
        </x-autocomplete>

            @if($referrer_id)
                <span>{{ $referrer_id }}</span>
            @endif

            <div class="flex items-center justify-between mt-4">
                <x-primary-button wire:click="addReferrer" wire:loading.attr="disabled">
                    Add Referrer
                </x-primary-button>
            </div>
        @endif
    </x-card>
    <x-live-feed />
</div>
