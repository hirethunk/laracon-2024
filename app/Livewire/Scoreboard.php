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

        $players_in_game = $this->player->state()->game()->players()
            ->filter(fn ($p) => $p->is_active)
            ->sortByDesc('score');

        $resigned_players = $this->player->state()->game()->players()
            ->filter(fn ($p) => ! $p->is_active);

        $this->players = $players_in_game->concat($resigned_players)
            ->map(fn ($p) => [
                'name' => $p->name,
                'id' => $p->id,
                'score' => $p->score,
                'is_active' => $p->is_active,
            ]);
    }

    public function render()
    {
        return view('livewire.scoreboard');
    }
}
