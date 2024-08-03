<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasUser;
use App\States\GameState;
use App\States\UserState;
use Thunk\Verbs\Event;

class UserPromotedToAdmin extends Event
{
	use HasUser;
	use HasGame;

    public function applyToUser(UserState $state)
    {
        $state->is_admin_for->push($this->game_id);
    }

    public function applyToGame(GameState $state)
    {
        $state->admin_user_ids->push($this->user_id);
    }
}
