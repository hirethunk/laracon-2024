<?php

namespace App\States;

use App\Models\Player;
use Thunk\Verbs\State;
use Illuminate\Support\Collection;

class PlayerState extends State
{
    public string $name;

    public int $user_id;

    public int $game_id;

    public Collection $downvotes;

    public Collection $upvotes;

    public Collection $ballots_cast;

    public function model()
    {
        return Player::find($this->id);
    }

    public function score()
    {
        return $this->upvotes->sum('votes') - $this->downvotes->sum('votes');
    }

    public function lastVotedAt()
    {
        return $this->ballots_cast->max('voted_at');
    }
}
