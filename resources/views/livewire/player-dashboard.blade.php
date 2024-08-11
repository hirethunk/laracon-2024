<div wire:poll>
    @if($this->game_is_active)
        <p class="pb-8 text-center text-sm">
            Game ends {{ $this->game->state()->ends_at->diffForHumans() }} 
            (5pm CST August 28)
        </p>
    @endif

    <div class="space-y-8">
        @if ($this->game_is_active)
            @if($this->modifier)
                <x-card>
                    <h2 class="text-2xl font-bold text-gold-500">{{ $this->modifier['title'] }}</h2>
                    <p class="mt-4">{{ $this->modifier['description'] }}</p>
                    <p class="mt-4 text-xs italic">Throughout the game, there will be modifiers here that change the rules.</p>
                </x-card>
            @endif

            @if ($this->player->is_active)
                <livewire:voting-card :player="$this->player"/>
            @endif

            <livewire:resignation-card :player="$this->player"/>
        @else
            <x-card>
                <h2 class="text-2xl font-bold text-gold-500">Thanks for playing</h2>
                <p class="mt-4">Tip your waiters, drive safe, and <a href="https://thunk.dev" class="text-blue-400">hire Thunk</a>.</p>
            </x-card>
        @endif

        @if($this->show_scoreboard)
            <livewire:scoreboard :player="$this->player"/>
        @endif
    </div>
</div>
