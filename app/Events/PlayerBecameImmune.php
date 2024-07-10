<?php

namespace App\Events;

use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use Illuminate\Support\Carbon;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class PlayerBecameImmune extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public string $source;

    public Carbon $is_immune_until;

    #[StateId(GameState::class)]
    public int $game_id;

    public function applyToPlayer(PlayerState $state)
    {
        $state->is_immune_until = $this->is_immune_until;
    }
}
