<?php

namespace App\States;

use App\Models\Player;
use Carbon\Carbon;
use Thunk\Verbs\State;

class PlayerState extends State
{
    public string $name;

    public int $user_id;

    public int $game_id;

    public array $downvotes;

    public array $upvotes;

    public array $ballots_cast;

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
}
