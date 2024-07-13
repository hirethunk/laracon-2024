<?php

namespace App\Events;

use App\Models\Game;
use App\States\GameState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class GameEnded extends Event
{
    #[StateId(GameState::class)]
    public int $game_id;

    public function apply(GameState $state)
    {
        $state->is_active = false;
    }

    public function handle()
    {
        $game = Game::find($this->game_id);

        $game->update([
            'status' => 'ended',
        ]);
    }
}
