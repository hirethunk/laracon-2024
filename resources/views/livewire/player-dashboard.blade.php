<div>
    @if ($this->player->is_active)
        <livewire:voting-card :player="$this->player"/>
    @endif
    
    @if ($this->player->is_active)
        <livewire:resignation-card :player="$this->player"/>
    @endif
    <livewire:scoreboard :player="$this->player"/>
</div>
