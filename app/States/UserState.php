<?php

namespace App\States;

use App\Models\User;
use Illuminate\Support\Collection;
use Thunk\Verbs\State;

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

    public function currentPlayer(): ?PlayerState
    {
		return state($this->current_player_id, PlayerState::class);
	}
	
	public function referrer(): ?PlayerState
	{
		return state($this->referrer_player_id, PlayerState::class);
    }
}
