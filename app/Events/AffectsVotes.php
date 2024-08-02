<?php

namespace App\Events;

use App\Models\Player;
use App\States\PlayerState;

trait AffectsVotes
{
	protected function upvotePlayer(
		PlayerState $state,
		int $source,
		string $type,
		int $votes = 1,
	): void {
		$state->upvotes[] = [
			'source' => $source,
			'votes' => $votes,
			'type' => $type,
		];
	}
	
	protected function downvotePlayer(
		PlayerState $state,
		int $source,
		string $type,
		int $votes = 1,
	): void {
		$state->downvotes[] = [
			'source' => $source,
			'votes' => $votes,
			'type' => $type,
		];
	}
	
	protected function syncPlayerScore(PlayerState $state): void
	{
		$player = Player::find($state->id);
		$player->score = $state->score();
		$player->save();
	}
}
