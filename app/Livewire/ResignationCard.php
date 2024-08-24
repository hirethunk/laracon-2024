<?php

namespace App\Livewire;

use App\Events\PlayerResigned;
use App\Models\Player;
use App\States\PlayerState;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ResignationCard extends Component
{
    public Player $player;

    public Collection $players;

    public ?string $search = '';

    public null|int|string $beneficiary_id = null;

    #[Computed]
    public function beneficiary()
    {
        return $this->players->first(fn ($p) => $p->id === $this->player->state()->beneficiary_id)->user->name ?? null;
    }

    #[Computed]
    public function options()
    {
        return $this->players->filter(function ($p) {
            if (isset($this->search)) {
                return stripos($p->user->name, $this->search) !== false;
            }
        })
        ->sortBy(fn ($p) => $p->user->name)
        ->mapWithKeys(fn ($p) => [$p->id => $p->user->name]);
    }

    public function mount(Player $player)
    {
        $this->initializeProperties($player);
    }

    public $rules = [
        'beneficiary_id' => 'integer|exists:players,id',
    ];

    public function initializeProperties(Player $player)
    {
        $this->player = $player;

        $this->setPlayers();
    }

    public function setPlayers()
    {
        $this->players = $this->player->game->players
            ->reject(fn ($p) => $p->id === $this->player->id)
            ->filter(fn ($p) => $p->state()->is_active)
            ->sortBy(fn ($p) => $p->name);
    }

    public function resign()
    {
        $this->beneficiary_id = (int) $this->beneficiary_id;

        $this->validate();

        if (! PlayerState::load($this->beneficiary_id)->is_active) {
            $this->beneficiary_id = null;

            session()->flash('error', 'Beneficiary has resigned. Please select another player.');

            $this->setPlayers();

            return;
        }

        PlayerResigned::fire(
            player_id: $this->player->id,
            game_id: $this->player->game->id,
            beneficiary_id: $this->beneficiary_id,
        );

        session()->flash('event', 'PlayerResigned');

        return redirect()->route('player-dashboard');
    }

    public function render()
    {
        return view('livewire.resignation-card');
    }
}
