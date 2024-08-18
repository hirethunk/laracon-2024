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

    public bool $is_active;

    public int $user_id;

    public int $game_id;

    public array $downvotes;

    public array $upvotes;

    public array $ballots_cast;

    public int $beneficiary_id;

    public $ally_id;

    public int $code_to_give_to_ally;

    public bool $has_connected_with_ally;

    public string $prisoners_dilemma_choice;

    public Carbon $is_immune_until;

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

    public function score()
    {
        return collect($this->upvotes)->sum('votes') - collect($this->downvotes)->sum('votes');
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
        $mod = $this->game()->activeModifier();

        return $mod && $mod['slug'] === 'first-shall-be-last' && $this->score() > 0;
    }

    public function cannotBeDownvoted(): bool
    {
        $mod = $this->game()->activeModifier();

        if ($mod && $mod['slug'] === 'first-shall-be-last' && $this->score() < 0) {
            return true;
        }

        return $this->is_immune_until > now();
    }
}
