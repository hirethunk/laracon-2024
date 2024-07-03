<div wire:poll class="space-y-12">
    <x-live-feed />

    @if ($this->player->is_active)
        <livewire:voting-card :player="$this->player"/>
    @endif

    <livewire:resignation-card :player="$this->player"/>
    <livewire:scoreboard :player="$this->player"/>
</div>
