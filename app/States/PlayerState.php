<?php

namespace App\States;

use App\Models\Player;
use Carbon\Carbon;
use Thunk\Verbs\State;
use Thunk\VerbsHistory\States\Traits\HasHistory;

class PlayerState extends State
{
    use HasHistory;

    public string $name;
    public int $score = 0;

    public int $user_id;
    public int $game_id;
    public bool $is_active;

    // used for buddy system
    public array $ballots_cast;
    public bool $buddy_system_reward_received = false;
    
    // used for resign and kingmake mechanic
    public int $beneficiary_id;
    
    // used for secret alliances
    public $ally_id;
    public int $code_to_give_to_ally;
    public bool $has_connected_with_ally;
    public string $prisoners_dilemma_choice;

    public Carbon $is_immune_until;

    // used to lock people out for submitting invalid codes.
    public Carbon $can_submit_code_at;

    public function model()
    {
        return Player::find($this->id);
    }

    public function game()
    {
        return GameState::load($this->game_id);
    }

    public function ally()
    {
        return $this->ally_id ? PlayerState::load($this->ally_id) : null;
    }

    public function canVote(): bool
    {
        $ballots = collect($this->ballots_cast);

        if ($ballots->count() === 0) {
            return true;
        }

        if ($this->lastVotedAt()->addHour() < now()) {
            return true;
        }

        return false;
    }

    public function lastVotedAt(): Carbon
    {
        return collect($this->ballots_cast)->count() > 0
            ? Carbon::parse(collect($this->ballots_cast)->max('voted_at'))
            : $this->game()->starts_at;
    }

    public function canSubmitCode(): bool
    {
        return $this->can_submit_code_at < now();
    }

    public function cannotBeUpvoted(): bool
    {
        return $this->game()->modifierIsActive('first-shall-be-last')
            && $this->score > 0;
    }

    public function cannotBeDownvoted(): bool
    {
        return $this->is_immune_until > now()
            || (
                $this->game()->modifierIsActive('first-shall-be-last')
                && $this->score < 0
            );
    }
}
