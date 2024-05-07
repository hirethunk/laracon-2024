<?php

namespace App\Events;

use App\Models\Game;
use App\States\GameState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class GameCreated extends Event
{
    #[StateId(GameState::class)]
    public ?int $game_id = null;

    public string $name;

    public function apply(GameState $state)
    {
        $state->name = $this->name;

        $state->user_ids_awaiting_approval = collect();

        $state->player_ids = collect();
    }

    public function handle()
    {
        Game::create([
            'id' => $this->game_id,
        ]);
    }
}
