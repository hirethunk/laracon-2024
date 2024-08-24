<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;

class Scoreboard extends Component
{
    public Player $player;

    public ?string $search = '';

    public function showScoreboard(): bool
    {
        $mod = $this->player->game->state()->activeModifier();

        return $mod
            ? $mod['slug'] !== 'blackout'
            : true;
    }

    #[Computed]
    public function players()
    {
        $players = $this->player->state()->game()->players();

        $players_in_game = $players
            ->filter(fn ($p) => $p->is_active)
            ->sortByDesc('score');

        $resigned_players = $players
            ->filter(fn ($p) => ! $p->is_active);

        return $players_in_game
            ->concat($resigned_players)
            ->map(fn ($p) => [
                'name' => $p->name,
                'id' => $p->id,
                'score' => $p->score,
                'is_active' => $p->is_active,
            ]);
    }

    #[Computed]
    public function options()
    {
        return $this->players->filter(function ($player) {
            if (isset($this->search)) {
                return stripos($player['name'], $this->search) !== false
                    || $player['is_active'] === true && stripos((string)$player['score'], $this->search) !== false
                    || $player['is_active'] === false && stripos('resigned', $this->search) !== false;
            }
        });
    }

    public function mount(Player $player)
    {
        $this->player = $player;
    }

    public function render()
    {
        return view('livewire.scoreboard');
    }
}
