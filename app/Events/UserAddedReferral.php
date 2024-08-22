<?php

namespace App\Events;

use App\States\GameState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserAddedReferral extends Event
{
    #[StateId(UserState::class)]
    public int $user_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public int $referrer_player_id;

    public function validate(GameState $game)
    {
        $this->assert(
            $game->player_ids->contains($this->referrer_player_id),
            'Referrer is not in game.'
        );
    }

    public function applyToUser(UserState $user)
    {
        $user->referrer_player_id = $this->referrer_player_id;
    }
}
