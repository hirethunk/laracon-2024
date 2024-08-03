<?php

namespace App\Events\Concerns;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

trait HasPlayer
{
	#[StateId(PlayerState::class, 'player')]
	public int $player_id;
	
	public function validateHasPlayer(): void
	{
		// If the event is not part of a game, skip game assertion
		if (! $game = $this->states()->firstOfType(GameState::class)) {
			return;
		}
		
		$this->assert(
			assertion: $game->isPlayer($this->player_id),
			exception: 'Player is not in the game.'
		);
	}
	
	public function player(): PlayerState
	{
		return $this->states()->get('player');
	}
}
