<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasUser;
use App\States\GameState;
use App\States\UserState;
use Thunk\Verbs\Event;

class UserPromotedToAdmin extends Event
{
    use HasGame;
    use HasUser;

    public function applyToUser(UserState $user)
    {
        $user->is_admin_for->push($this->game_id);
    }

    public function applyToGame(GameState $game)
    {
        $game->admin_user_ids->push($this->user_id);
    }
}
