<div wire:poll>
    <x-live-feed class="mt-10" />
    @if ($this->player->is_active)
        <livewire:voting-card :player="$this->player"/>
    @endif

    <livewire:resignation-card :player="$this->player"/>
    <livewire:scoreboard :player="$this->player"/>
</div>
