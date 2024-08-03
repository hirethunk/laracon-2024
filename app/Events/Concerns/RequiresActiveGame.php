<?php

namespace App\Events\Concerns;

use App\States\GameState;

trait RequiresActiveGame
{
    public function validateRequiresActiveGame(GameState $game): void
    {
        $this->assert(
            assertion: $game->is_active,
            exception: 'The game is over.'
        );
    }
}
