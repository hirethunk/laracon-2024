<div wire:poll>
    <x-live-feed class="mt-10" />
    @if ($this->player->is_active)
        <livewire:voting-card :player="$this->player"/>
    @endif
    <livewire:scoreboard :player="$this->player"/>
    <div class="bg-gold-500">
        <x-history.feed :state="$this->player->state()" subHistory="default" />
    </div>
    @if ($this->player->is_active)
        <livewire:resignation-card :player="$this->player"/>
    @endif
</div>
