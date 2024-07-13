<?php

namespace App\Events;

use App\States\GameState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserPromotedToAdmin extends Event
{
    #[StateId(UserState::class)]
    public int $user_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public function applyToUser(UserState $state)
    {
        $state->is_admin_for->push($this->game_id);
    }

    public function applyToGame(GameState $state)
    {
        $state->admin_user_ids->push($this->user_id);
    }
}
