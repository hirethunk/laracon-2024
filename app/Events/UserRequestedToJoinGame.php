<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasUser;
use App\States\GameState;
use Thunk\Verbs\Event;

class UserRequestedToJoinGame extends Event
{
    use HasGame;
    use HasUser;

    public function validate(GameState $game)
    {
        $this->assert(
            assertion: ! $game->isAwaitingApproval($this->user_id),
            exception: 'User has already requested to join this game.'
        );

        $this->assert(
            assertion: ! $game->isPlayer(user: $this->user_id),
            exception: 'User is already in the game.'
        );
    }

    public function applyToGame(GameState $game)
    {
        $game->user_ids_awaiting_approval->push($this->user_id);
    }
}
