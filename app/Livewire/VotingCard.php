<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Player;
use Livewire\Component;
use App\Events\PlayerVoted;
use Thunk\Verbs\Facades\Verbs;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class VotingCard extends Component
{
    #[Computed]
    public function game(): Game
    {
        return $this->player->game;
    }

    #[Computed]
    public function upvoteOptions()
    {
        return $this->game->players
            ->reject(fn($p) => $p->id === $this->player->id)
            ->filter(fn($p) => $p->state()->is_active)
            ->sortBy(fn($p) => $p->name);
    }

    #[Computed]
    public function downvoteOptions()
    {
        return $this->game->players
            ->reject(fn($p) => $p->id === $this->player->id
                || $p->state()->isImmune()
            )
            ->filter(fn($p) => $p->state()->is_active)
            ->sortBy(fn($p) => $p->name);
    }

    public Player $player;

    public Collection $players;

    public bool $player_can_vote;

    public ?int $downvote_target_id = null;

    public ?int $upvote_target_id = null;

    public $rules = [
        'downvote_target_id' => 'integer|exists:players,id',
        'upvote_target_id' => 'integer|exists:players,id',
    ];
    
    public function mount(Player $player)
    {
        $this->initializeProperties($player);
    }

    public function initializeProperties(Player $player)
    {
        $this->player = $player;

        $this->player_can_vote = Verbs::isAuthorized(
            PlayerVoted::make(
                player_id: $this->player->id,
                game_id: $this->game->id,
            )->event
        );
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

        session()->flash('event', 'PlayerVoted');

        return redirect()->route('player-dashboard', $this->game->id);
    }

    public function render()
    {
        return view('livewire.voting-card');
    }
}
