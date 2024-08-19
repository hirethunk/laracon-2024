<?php

namespace App\Livewire;

use App\Events\PlayerVoted;
use App\Models\Game;
use App\Models\Player;
use App\States\PlayerState;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class VotingCard extends Component
{
    public Player $player;

    public Collection $players;

    public bool $player_can_vote;

    public $downvote_target_id = null;

    public $upvote_target_id = null;

    public $downvote_search = '';

    public $upvote_search = '';

    public $rules = [
        'downvote_target_id' => 'integer|exists:players,id',
        'upvote_target_id' => 'integer|exists:players,id',
    ];

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

    #[Computed]
    public function downvoteOptions()
    {
        return $this->calculateDownvoteOptions();
    }

    #[Computed]
    public function upvoteOptions()
    {
        return $this->calculateUpvoteOptions();
    }

    public function mount(Player $player)
    {
        $this->player = $player;
        $this->setVoteeOptions();
    }

    public function calculateDownvoteOptions()
    {
        return $this->game->players
            ->reject(fn ($p) => $p->id === $this->player->id
                || $p->state()->cannotBeDownvoted()
            )
            ->filter(fn ($p) => $p->state()->is_active)
            ->filter(function ($p) {
                if (isset($this->downvote_search)) {
                    return stripos($p->user->name, $this->downvote_search) !== false;
                }
                return true;
            })
            ->sortBy(fn ($p) => $p->name);
    }

    public function calculateUpvoteOptions()
    {
        return $this->game->players
            ->reject(fn ($p) => $p->id === $this->player->id
                || $p->state()->cannotBeUpvoted()
            )
            ->filter(fn ($p) => $p->state()->is_active)
            ->filter(function ($p) {
                if (isset($this->upvote_search)) {
                    return stripos($p->user->name, $this->upvote_search) !== false;
                }
                return true;
            })
            ->sortBy(fn ($p) => $p->name);
    }

    public function setVoteeOptions()
    {
        unset($this->downvoteOptions);
        unset($this->upvoteOptions);
    }

    public function vote()
    {
        $this->validate();

        if (! PlayerState::load($this->downvote_target_id)->is_active) {
            $this->downvote_target_id = null;

            session()->flash('error', 'Downvotee has resigned. Please select another player.');

            $this->setVoteeOptions();

            return;
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
            upvotee_id: (int) $this->upvote_target_id,
            downvotee_id: (int) $this->downvote_target_id,
        );

        session()->flash('event', 'PlayerVoted');

        return redirect()->route('player-dashboard', $this->game->id);
    }

    public function render()
    {
        return view('livewire.voting-card');
    }
}
