<div wire:poll class="space-y-4">
    <div class="pt-2">
        <x-live-feed />
    </div>

    @if ($this->player->is_active)
        <livewire:voting-card :player="$this->player"/>
    @endif

    <livewire:resignation-card :player="$this->player"/>
    <livewire:scoreboard :player="$this->player"/>
</div>
