<?php

namespace App\States;

use App\Models\User;
use Thunk\Verbs\State;
use App\States\PlayerState;
use Illuminate\Support\Collection;

class UserState extends State
{
    public string $name;

    public Collection $is_admin_for;

    public ?int $current_game_id = null;

    public ?int $current_player_id = null;

    public ?int $referrer_player_id = null;

    public function model()
    {
        return User::find($this->id);
    }

    public function currentPlayer(): PlayerState|null
    {
        return $this->current_player_id
            ? PlayerState::load($this->current_player_id)
            : null;
    }
}
