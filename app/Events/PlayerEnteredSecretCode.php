<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\RequiresActiveGame;
use Thunk\Verbs\Event;

class PlayerEnteredSecretCode extends Event
{
    use HasGame;
	use HasPlayer;
	use RequiresActiveGame;

    public string $secret_code;

    public function fired()
    {
        $game = $this->game();

        $code_is_unused = collect($game->unused_codes)->contains($this->secret_code);

        $code_is_used = collect($game->used_codes)->contains($this->secret_code);

        if ($code_is_used) {
            return;
        }

        if ($code_is_unused) {
            SecretCodeUsed::fire(
                player_id: $this->player_id,
                game_id: $this->game_id,
                secret_code: $this->secret_code,
            );

            return;
        }

        PlayerReceivedDownvote::fire(
            player_id: $this->player_id,
            voter_id: $this->player_id,
            game_id: $this->game_id,
            type: 'invalid-secret-code',
            amount: 1,
        );
    }
}
