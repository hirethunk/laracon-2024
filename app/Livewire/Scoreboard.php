<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;

class Scoreboard extends Component
{
    public Player $player;

    public Collection $players_collection;

    public ?string $search = '';

    public null|int|string $searched_player_id = null;

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
        return $this->player->state()->game()->players();
    }

    #[Computed]
    public function options()
    {
        return $this->players->filter(function ($player) {
            if (isset($this->search)) {
                return stripos($player->name, $this->search) !== false;
            }
        })->mapWithKeys(function ($player) {
            return [$player->id => $player->name];
        });
    }

    public function mount(Player $player)
    {
        $this->player = $player;

        $this->setPlayers();
    }

    public function setPlayers()
    {
        if ($this->searched_player_id) {
            $this->players_collection = $this->player->state()->game()->players()
                ->filter(fn ($p) => $p->id === $this->searched_player_id)
                ->map(fn ($p) => [
                    'name' => $p->name,
                    'id' => $p->id,
                    'score' => $p->score,
                    'is_active' => $p->is_active,
                ]);

            return;
        }

        $players_in_game = $this->player->state()->game()->players()
            ->filter(fn ($p) => $p->is_active)
            ->sortByDesc('score');

        $resigned_players = $this->player->state()->game()->players()
            ->filter(fn ($p) => ! $p->is_active);

        $this->players_collection = $players_in_game->concat($resigned_players)
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
