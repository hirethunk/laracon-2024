<?php

namespace App\Events;

use Thunk\Verbs\Event;
use App\States\GameState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class GameModifiersAddedToGame extends Event
{
    #[StateId(GameState::class)]
    public int $game_id;

    public array $modifiers;

    public function apply(GameState $state)
    {
        $state->modifiers = collect($this->modifiers);
    }
}
