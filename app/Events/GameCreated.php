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
    public function __construct(
        #[StateId(GameState::class)]
        public ?int $game_id,
        public string $name,
        public Carbon $starts_at,
        public array $modifiers = [],
        public array $secret_codes = [],
    ) {
        $template = new Laracon2024Template($this->starts_at);

        $this->modifiers = $template->modifiers();
        $this->secret_codes = $template::CODES;
    }

    public function apply(GameState $state)
    {
        $state->name = $this->name;

        $state->user_ids_approved = collect();

        $state->player_ids = collect();

        $state->admin_user_ids = collect();

        $state->unused_codes = $this->secret_codes;

        $state->used_codes = [];

        $state->starts_at = $this->starts_at;

        $state->ends_at = $this->starts_at->copy()->addHours(36);

        $state->modifiers = $this->modifiers;
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
