<?php

namespace App\States;

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Thunk\Verbs\State;

class GameState extends State
{
    public string $name;

    public Collection $user_ids_awaiting_approval;

    public Collection $player_ids;

    public Collection $user_ids_approved;

    public Collection $admin_user_ids;

    public array $unused_codes;

    public array $used_codes;

    public Carbon $starts_at;

    public Carbon $ends_at;

    public array $modifiers;

    public function activeModifier()
    {
        return collect($this->modifiers)
            ->filter(fn ($modifier) => Carbon::parse($modifier['starts_at']) <= now()
                && Carbon::parse($modifier['ends_at']) >= now()
            )->first();
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
