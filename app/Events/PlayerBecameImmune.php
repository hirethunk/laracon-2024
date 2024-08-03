<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\States\GameState;
use App\States\PlayerState;
use Illuminate\Support\Carbon;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerBecameImmune extends Event
{
    use HasPlayer;
	use HasGame;

    public string $source;

    public Carbon $is_immune_until;

    public function applyToPlayer(PlayerState $state)
    {
        $state->is_immune_until = $this->is_immune_until;
    }
}
