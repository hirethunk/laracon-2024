<?php

namespace App\Events;

use App\Models\Game;
use App\Modifiers\Laracon2024Template;
use App\States\GameState;
use Illuminate\Support\Carbon;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

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

        $state->unused_codes = [];

        $state->used_codes = [];

        $state->starts_at = $this->starts_at;

        $state->ends_at = $this->starts_at->copy()->addHours(36);

        $state->modifiers = [];
    }

    public function fired()
    {
        $template = new Laracon2024Template($this->state(GameState::class));

        GameModifiersAddedToGame::fire(
            game_id: $this->game_id,
            modifiers: $template->modifiers(),
        );

        SecretCodesAddedToGame::fire(
            game_id: $this->game_id,
            codes: $template::CODES,
        );
    }

    public function handle()
    {
        Game::create([
            'id' => $this->game_id,
            'name' => $this->name,
            'status' => 'active',
        ]);
    }
}
