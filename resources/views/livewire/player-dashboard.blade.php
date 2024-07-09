<div wire:poll>
    <x-live-feed class="mt-10" />

    @if($this->modifier)
        <x-card>

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
