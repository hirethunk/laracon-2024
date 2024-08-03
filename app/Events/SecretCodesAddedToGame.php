<?php

namespace App\Events;

use App\States\GameState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class SecretCodesAddedToGame extends Event
{
    public function __construct(
        #[StateId(GameState::class)]
        public int $game_id,
        public array $codes,
    ) {}

    public function apply(GameState $state)
    {
        $state->unused_codes = collect($this->codes);
    }
}
