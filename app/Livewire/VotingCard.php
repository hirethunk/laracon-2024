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
        return $this->game->players
            ->reject(fn($p) => $p->id === $this->player->id)
            ->sortBy(fn($p) => $p->name);
    }

    public function mount()
    {
        $this->initializeProperties();
    }

    public bool $player_can_vote;

    public ?int $downvote_target_id = null;

    public ?int $upvote_target_id = null;

    public $rules = [
        'downvote_target_id' => 'integer|exists:players,id',
        'upvote_target_id' => 'integer|exists:players,id',
    ];

    public function initializeProperties()
    {
        $this->player_can_vote = Verbs::isAuthorized(
            PlayerVoted::make(
                player_id: $this->player->id,
                game_id: $this->game->id,
            )->event
        );

        dd($this->player);
    }

    public function vote()
    {
        $this->validate();

        PlayerVoted::fire(
            player_id: $this->player->id,
            game_id: $this->game->id,
            upvotee_id: $this->upvote_target_id,
            downvotee_id: $this->downvote_target_id,
        );
    }

    public function render()
    {
        return view('livewire.voting-card');
    }
}
