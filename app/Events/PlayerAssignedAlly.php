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

    public ?int $code = null;

    public function applyToPlayer(PlayerState $state)
    {
        $this->code ??= rand(1000, 9999);

        $state->ally_id = $this->ally_id;
        $state->code_to_give_to_ally = $this->code;
    }
}
