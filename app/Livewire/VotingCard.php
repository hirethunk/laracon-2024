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

    public null|int|string $downvote_target_id = null;

    public null|int|string $upvote_target_id = null;

    public ?string $downvote_search = '';

    public ?string $upvote_search = '';

    public $rules = [
        'downvote_target_id' => 'integer|exists:players,id|required_with:upvote_target_id',
        'upvote_target_id' => 'integer|exists:players,id|required_with:downvote_target_id',
    ];

    protected $messages = [
        'upvote_target_id.required_with' => 'You must both upvote and downvote.',
        'downvote_target_id.required_with' => 'You must both upvote and downvote.',
        'upvote_target_id.integer' => 'You must select an upvote target.',
        'downvote_target_id.integer' => 'You must select a downvote target.',
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
    }

    public function calculateDownvoteOptions()
    {
        return $this->game->players
            ->reject(
                fn ($p) => $p->id === $this->player->id
                || $p->state()->cannotBeDownvoted()
            )
            ->filter(fn ($p) => $p->state()->is_active)
            ->filter(function ($p) {
                if (isset($this->downvote_search)) {
                    return stripos($p->user->name, $this->downvote_search) !== false;
                }
            })
            ->sortBy(fn ($p) => $p->user->name);
    }

    public function calculateUpvoteOptions()
    {
        return $this->game->players
            ->reject(
                fn ($p) => $p->id === $this->player->id
                || $p->state()->cannotBeUpvoted()
            )
            ->filter(fn ($p) => $p->state()->is_active)
            ->filter(function ($p) {
                if (isset($this->upvote_search)) {
                    return stripos($p->user->name, $this->upvote_search) !== false;
                }
            })
            ->sortBy(fn ($p) => $p->user->name);
    }

    public function setVoteeOptions()
    {
        unset($this->downvoteOptions);
        unset($this->upvoteOptions);
    }

    public function vote()
    {
        if (isset($this->downvote_target_id) && isset($this->upvote_target_id)) {
            $this->downvote_target_id = (int) $this->downvote_target_id;
            $this->upvote_target_id = (int) $this->upvote_target_id;
        }

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
            upvotee_id: $this->upvote_target_id,
            downvotee_id: $this->downvote_target_id,
        );

        session()->flash('event', 'PlayerVoted');

        return redirect()->route('player-dashboard');
    }

    public function render()
    {
        return view('livewire.voting-card');
    }
}
