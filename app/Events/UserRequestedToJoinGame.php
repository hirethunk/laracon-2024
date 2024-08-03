<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasUser;
use App\States\GameState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserRequestedToJoinGame extends Event
{
	use HasUser;
	use HasGame;

    public function validate()
    {
        $this->assert(
            $this->game()->user_ids_awaiting_approval->contains($this->user_id) === false,
            'User has already requested to join this game.'
        );

        $this->assert(
	        $this->game()->user_ids_approved->contains($this->user_id) === false,
            'User is already in the game.'
        );
    }

    public function applyToUser(UserState $state)
    {
        //
    }

    public function applyToGame(GameState $state)
    {
        $state->user_ids_awaiting_approval->push($this->user_id);
    }
}
