<?php

namespace App\Livewire;

use App\Models\Player;
use Illuminate\Support\Collection;
use Livewire\Component;

class Scoreboard extends Component
{
    public function mount(Player $player)
    {
        $this->initializeProperties($player);
    }

    public Player $player;

    public Collection $players;

    public function showScoreboard(): bool
    {
        $mod = $this->player->game->state()->activeModifier();

        return $mod
            ? $mod['slug'] !== 'blackout'
            : true;
    }

    public function initializeProperties(Player $player)
    {
        $this->player = $player;

        $this->players = $this->player->game->players
            ->filter(fn ($p) => $p->state()->is_active)
            ->sortByDesc('score');
    }

    public function render()
    {
        return view('livewire.scoreboard');
    }
}
