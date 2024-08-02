<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerEnteredSecretCode extends Event
{
	use ValidatesPlayerInActiveGame;
	use AffectsVotes;
	
    #[StateId(PlayerState::class)]
    public int $player_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public string $secret_code;
	
	public function apply()
	{
		$game = $this->state(GameState::class);
		$player = $this->state(PlayerState::class);
		
		$code_is_unused = collect($game->unused_codes)->contains($this->secret_code);
		$code_is_used = collect($game->used_codes)->contains($this->secret_code);
		
		// If code is valid and unused, apply it
		if ($code_is_unused) {
			$this->upvotePlayer($player, $this->player_id, $this->type);
			
			$game->unused_codes = collect($game->unused_codes)
				->filter(fn($code) => $code !== $this->secret_code)
				->toArray();
			
			$game->used_codes[] = $this->secret_code;
		}
		
		// If it's already been used, just don't do anything
		if ($code_is_used) {
			return;
		}
		
		// If the code is invalid, penalize the player
		$this->downvotePlayer($player, $this->player_id, 'invalid-secret-code');
	}
	
	public function handle()
	{
		$this->syncPlayerScore($this->state(PlayerState::class));
	}
}
