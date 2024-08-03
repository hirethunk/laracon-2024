<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Models\Game;
use App\States\GameState;
use Thunk\Verbs\Event;

class GameEnded extends Event
{
	use HasGame;

    public function apply(GameState $game)
    {
        $game->is_active = false;
    }

    public function handle()
    {
        $game = Game::find($this->game_id);

        $game->update([
            'status' => 'ended',
        ]);
    }
}
