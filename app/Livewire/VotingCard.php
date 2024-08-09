<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Player;
use Livewire\Component;
use App\Events\PlayerVoted;
use App\States\PlayerState;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;

class VotingCard extends Component
{
    #[Computed]
    public function game(): Game
    {
        return $this->player->game;
    }

    #[Computed]
    public function canVote()
    {
        return $this->player->state()->canVote();
    }

    public Player $player;

    public Collection $players;

    public Collection $upvote_options;

    public Collection $downvote_options;

    public bool $player_can_vote;

    public ?int $downvote_target_id = null;

    public ?int $upvote_target_id = null;

    public $rules = [
        'downvote_target_id' => 'integer|exists:players,id',
        'upvote_target_id' => 'integer|exists:players,id',
    ];

    public function mount(Player $player)
    {
        $this->player = $player;

        $this->setVoteeOptions();
    }

    public function setVoteeOptions()
    {
        $this->downvote_options = $this->game->players
            ->reject(fn ($p) => $p->id === $this->player->id
                || $p->state()->cannotBeDownvoted()
            )
            ->filter(fn ($p) => $p->state()->is_active)
            ->sortBy(fn ($p) => $p->name);

        $this->upvote_options = $this->game->players
            ->reject(fn ($p) => $p->id === $this->player->id
                || $p->state()->cannotBeUpvoted()
            )
            ->filter(fn ($p) => $p->state()->is_active)
            ->sortBy(fn ($p) => $p->name);
    }

    public function vote()
    {
        $this->validate();

        if (! PlayerState::load($this->downvote_target_id)->is_active) {
            $this->downvote_target_id = null;

            session()->flash('error', 'Downvotee has resigned. Please select another player.');

            $this->setVoteeOptions();
        }

        if (! PlayerState::load($this->upvote_target_id)->is_active) {
            $this->upvote_target_id = null;

            session()->flash('error', 'Upvotee has resigned. Please select another player.');

            $this->setVoteeOptions();

            return;
        }

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
