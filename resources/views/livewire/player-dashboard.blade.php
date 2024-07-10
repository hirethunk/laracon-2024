<div wire:poll>
    <x-live-feed class="mt-10" />

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

    @if($this->show_scoreboard)
        <livewire:scoreboard :player="$this->player"/>
    @endif
</div>
