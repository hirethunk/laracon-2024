<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;
use Illuminate\Support\Collection;

class Scoreboard extends Component
{
    public function mount(Player $player)
    {
        $this->initializeProperties($player);
    }

    public Player $player;

    public Collection $players;

    public function initializeProperties(Player $player)
    {
        $this->player = $player;

        $this->players = $this->player->game->players
            ->filter(fn($p) => $p->state()->is_active)
            ->sortByDesc('score');
    }

    public function render()
    {
        return view('livewire.scoreboard');
    }
}
