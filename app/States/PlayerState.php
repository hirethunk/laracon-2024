<?php

namespace App\States;

use Carbon\Carbon;
use App\Models\Player;
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

    public Carbon $is_immune_until;

    public function model()
    {
        return Player::find($this->id);
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

        if($this->lastVotedAt()->addHour() < now()) {
            return true;
        }

        return false;
    }

    public function lastVotedAt(): Carbon
    {
        return Carbon::parse(collect($this->ballots_cast)->max('voted_at'));
    }

    public function isImmune()
    {
        dd(
            $this->is_immune_until,
            now(),
            $this->is_immune_until > now()
        );
        return $this->is_immune_until > now();
    }
}
