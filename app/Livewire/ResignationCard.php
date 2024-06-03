<?php

namespace App\Livewire;

use App\Models\Player;
use Livewire\Component;
use App\Events\PlayerResigned;
use Illuminate\Support\Collection;

class ResignationCard extends Component
{
    public function mount(Player $player)
    {
        $this->initializeProperties($player);
    }

    public Player $player;

    public Collection $players;

    public ?int $beneficiary_id = null;

    public $rules = [
        'beneficiary_id' => 'integer|exists:players,id',
    ];

    public function initializeProperties(Player $player)
    {
        $this->player = $player;

        $this->players = $this->player->game->players
            ->reject(fn($p) => $p->id === $this->player->id)
            ->filter(fn($p) => $p->state()->is_active)
            ->sortBy(fn($p) => $p->name);
    }

    public function resign()
    {
       $this->validate();
       
        PlayerResigned::fire(
            player_id: $this->player->id,
            game_id: $this->player->game->id,
            beneficiary_id: $this->beneficiary_id,
        );
    }

    public function render()
    {
        return view('livewire.resignation-card');
    }
}
