<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerEnteredSecretCode extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public string $secret_code;

    public function authorize()
    {
        $this->assert(
            GameState::load($this->game_id)->player_ids->contains($this->player_id),
            'Player is not in the game.'
        );

        $this->assert(
            GameState::load($this->game_id)->ends_at > now(),
            'The game is over.'
        );
    }

    public function fired()
    {
        $game = $this->state(GameState::class);

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
