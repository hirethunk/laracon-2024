<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class AdminRejectedNewPlayer extends Event
{
    public int $admin_id;

    #[StateId(UserState::class)]
    public int $user_id;

    #[StateId(GameState::class)]
    public int $game_id;

    #[StateId(PlayerState::class)]
    public ?int $player_id = null;

    public function authorize()
    {
        $this->assert(
            $this->state(UserState::class)->is_admin_for->contains($this->admin_id),
            'Only admins can reject new players.'
        );
    }

    public function validate()
    {
        $this->assert(
            ! $this->state(GameState::class)->players()->map(fn ($p) => $p->user_id)->contains($this->user_id),
            'User is already in the game.'
        );

        $this->assert(
            GameState::load($this->game_id)->ends_at > now(),
            'The game is over.'
        );
    }

    public function applyToGame(GameState $state)
    {
        $state->user_ids_awaiting_approval = $state->user_ids_awaiting_approval
            ->reject(fn ($id) => $id === $this->user_id);
    }
}
