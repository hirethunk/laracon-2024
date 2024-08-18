<?php

namespace App\Events;

use App\Models\Code;
use App\States\CodeState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class SecretCodeCreated extends Event
{
    #[StateId(CodeState::class)]
    public ?int $code_id = null;

    public string $code;

    public int $game_id;

    public function apply(CodeState $state)
    {
        $state->code = $this->code;

        $state->game_id = $this->game_id;

        $state->is_used = false;
    }

    public function handle()
    {
        Code::create([
            'id' => $this->code_id,
            'code' => $this->code,
            'game_id' => $this->game_id,
            'is_used' => false,
        ]);
    }
}
