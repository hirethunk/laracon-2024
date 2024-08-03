<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\States\GameState;
use Thunk\Verbs\Event;

class GameModifiersAddedToGame extends Event
{
	use HasGame;

    public array $modifiers;

    public function apply(GameState $game)
    {
        $game->modifiers = $this->modifiers;
    }
}
