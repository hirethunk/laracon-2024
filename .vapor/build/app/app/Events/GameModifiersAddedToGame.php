<?php

namespace App\Events;

use App\States\GameState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class GameModifiersAddedToGame extends Event
{
    #[StateId(GameState::class)]
    public int $game_id;

    public array $modifiers;

    public function apply(GameState $state)
    {
        $state->modifiers = $this->modifiers;
    }
}
