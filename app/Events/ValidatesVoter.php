<?php

namespace App\Events;

use App\Models\Player;
use App\States\GameState;
use App\States\PlayerState;

trait ValidatesVoter
{
	public function validateGameForHasVoter(GameState $state): void
	{
		$this->assert($state->player_ids->contains($this->voter_id), 'Voter is not in the game.');
	}
}
