<?php

namespace App\Events\Concerns;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

trait HasGame
{
	#[StateId(GameState::class, 'game')]
	public int $game_id;
	
	protected function game(): GameState
	{
		return $this->states()->get('game');
	}
}
