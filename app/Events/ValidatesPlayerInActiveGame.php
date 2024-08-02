<?php

namespace App\Events;

use App\Models\Player;
use App\States\GameState;
use App\States\PlayerState;

trait ValidatesPlayerInActiveGame
{
	public function validateGameIsActiveForPlayer(GameState $state): void
	{
		$this->assert($state->is_active, 'The game is over.');
		$this->assert($state->player_ids->contains($this->player_id), 'Player is not in the game.');
	}
}
