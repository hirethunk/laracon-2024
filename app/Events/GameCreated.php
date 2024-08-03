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

    public function apply(GameState $game)
    {
        $game->name = $this->name;
        $game->user_ids_awaiting_approval = collect();
        $game->user_ids_approved = collect();
        $game->player_ids = collect();
        $game->admin_user_ids = collect();
        $game->unused_codes = collect();
        $game->used_codes = collect();
        $game->starts_at = $this->starts_at;
        $game->modifiers = [];
        $game->is_active = true;
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
