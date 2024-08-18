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

    public int $game_id;

    public string $secret_code;

    public function authorize()
    {
        $this->assert(
            PlayerState::load($this->player_id)->game_id === $this->game_id,
            'Player is not in the game.'
        );

        // @todo this seems important. But maybe we can avoid it?
        $this->assert(
            GameState::load($this->game_id)->ends_at > now(),
            'The game is over.'
        );
    }

    public function applyToGame(GameState $game)
    {
        if (collect($game->unused_codes)->contains($this->secret_code)) {
            $game->unused_codes = collect($game->unused_codes)
                ->filter(fn ($code) => $code !== $this->secret_code)
                ->toArray();

            $game->used_codes[] = $this->secret_code;
        }
    }

    public function applyToPlayer(PlayerState $state)
    {
        $game = $this->state(GameState::class);

        $code_is_unused = collect($game->unused_codes)->contains($this->secret_code);

        $code_is_used = collect($game->used_codes)->contains($this->secret_code);

        if ($code_is_used) {
            return;
        }

        if ($code_is_unused) {
            $state->upvotes[] = [
                'source' => $this->player_id,
                'votes' => 1,
                'type' => 'secret-code-reward',
            ];

            return;
        }

        $state->downvotes[] = [
            'source' => $this->player_id,
            'votes' => 1,
            'type' => 'invalid_secret_code',
        ];
    }
}
