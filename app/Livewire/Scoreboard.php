<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

class Scoreboard extends Component
{
    public function mount(Player $player)
    {
        $this->initializeProperties($player);
    }

    public Player $player;

    public Game $game;

    public Collection $players;

    #[On('echo:games.{game.id},ScoreChanged')]
    public function initializeProperties(Player $player)
    {
        $this->player = $this->player;

        $this->game = $this->player->game;

        $this->players = $this->player->game->players
            ->sortByDesc('score');
    }

    public function render()
    {
        return view('livewire.scoreboard');
    }
}
