<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class SecretCodeUsed extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    #[StateId(GameState::class)]
    public int $game_id;

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

    public function applyToPlayer(PlayerState $state)
    {
        $state->upvotes[] = [
            'source' => $this->player_id,
            'votes' => 1,
            'type' => 'secret-code-reward',
        ];
    }
}
