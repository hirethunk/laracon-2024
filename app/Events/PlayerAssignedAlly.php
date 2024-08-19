<?php

namespace App\Events;

use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerAssignedAlly extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $ally_id;

    public int $game_id;

    public function applyToPlayer(PlayerState $state)
    {
        $state->ally_id = $this->ally_id;
    }
}
