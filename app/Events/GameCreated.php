<?php

namespace App\Events;

use App\Models\Game;
use Thunk\Verbs\Event;
use App\States\GameState;
use Illuminate\Support\Carbon;
use App\Modifiers\Laracon2024Template;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class GameCreated extends Event
{
    #[StateId(GameState::class)]
    public ?int $game_id = null;

    public string $name;

    public Carbon $starts_at;

    public function apply(GameState $state)
    {
        $state->name = $this->name;

        $state->user_ids_awaiting_approval = collect();

        $state->user_ids_approved = collect();

        $state->player_ids = collect();

        $state->admin_user_ids = collect();

        $state->starts_at = $this->starts_at;

        $state->modifiers = [];

        $state->is_active = true;
    }

    public function fired()
    {
        GameModifiersAddedToGame::fire(
            game_id: $this->game_id,
            modifiers: (new Laracon2024Template($this->state(GameState::class)))->modifiers(),
        );
    }

    public function handle()
    {
        Game::create([
            'id' => $this->game_id,
            'name' => $this->name,
        ]);
    }
}
