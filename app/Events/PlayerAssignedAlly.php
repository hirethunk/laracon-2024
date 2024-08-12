<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerAssignedAlly extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $ally_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public function applyToGame(GameState $state)
    {
        // @todo why does this function need to exist?
    }

    public function applyToPlayer(PlayerState $state)
    {
        $state->ally_id = $this->ally_id;
    }
}
