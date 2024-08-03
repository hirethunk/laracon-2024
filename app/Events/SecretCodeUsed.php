<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\States\GameState;
use Thunk\Verbs\Event;

class SecretCodeUsed extends Event
{
	use HasPlayer;
	use HasGame;

    public string $secret_code;

    public function applyToGame(GameState $game)
    {
        if (collect($game->unused_codes)->contains($this->secret_code)) {
            $game->unused_codes = collect($game->unused_codes)
                ->filter(fn ($code) => $code !== $this->secret_code)
                ->toArray();

            $game->used_codes[] = $this->secret_code;
        }
    }

    public function fired()
    {
        PlayerReceivedUpvote::fire(
            player_id: $this->player_id,
            voter_id: $this->player_id,
            game_id: $this->game_id,
            type: 'secret-code-reward',
            amount: 1,
        );
    }
}
