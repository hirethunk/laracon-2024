<?php

namespace App\States;

use App\Models\Game;
use App\Models\User;
use Thunk\Verbs\State;
use App\States\PlayerState;
use Illuminate\Support\Collection;

class GameState extends State
{
    public string $name;

    public Collection $user_ids_awaiting_approval;

    public Collection $player_ids;

    public function model()
    {
        return Game::find($this->id);
    }

    public function usersAwaitingApproval()
    {
        return collect($this->user_ids_awaiting_approval)->map(fn ($id) => User::find($id));
    }

    public function players()
    {
        return collect($this->player_ids)->map(fn ($id) => PlayerState::load($id));
    }
}
