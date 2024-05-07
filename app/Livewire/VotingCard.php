<?php

namespace App\Livewire;

use App\Events\PlayerVoted;
use App\Models\Game;
use App\Models\User;
use App\Models\Player;
use Livewire\Component;
use Thunk\Verbs\Facades\Verbs;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class VotingCard extends Component
{
    #[Computed]
    public function user(): User
    {
        return Auth::user();
    }

    #[Computed]
    public function player(): Player
    {
        return $this->user->player;
    }

    #[Computed]
    public function game(): Game  
    {
        return $this->player->game;
    }

    #[Computed]
    public function players(): Collection
    {
        return $this->game->players;
    }

    public function mount()
    {
        $this->initializeProperties();
    }

    public bool $player_can_vote;

    public function initializeProperties()
    {
        $this->player_can_vote = Verbs::isAuthorized(
            PlayerVoted::make(
                player_id: $this->player->id,
                game_id: $this->game->id,
            )->event
        );
    }

    public function render()
    {
        return view('livewire.voting-card');
    }
}
