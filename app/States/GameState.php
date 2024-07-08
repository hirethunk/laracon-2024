<?php

namespace App\States;

use App\Models\Game;
use App\Models\User;
use Thunk\Verbs\State;
use App\States\PlayerState;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GameState extends State
{
    public string $name;

    public bool $is_active;

    public Collection $user_ids_awaiting_approval;

    public Collection $player_ids;

    public Collection $user_ids_approved;

    public Collection $admin_user_ids;

    public Carbon $starts_at;

    public Collection $modifiers;

    public function activeModifier()
    {
        return $this->modifiers->firstWhere(fn ($modifier) => 
            $modifier['starts_at'] <= Carbon::now() && 
            $modifier['ends_at'] >= Carbon::now()
        );
    }

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
