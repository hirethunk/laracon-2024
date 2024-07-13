<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class AdminApprovedNewPlayer extends Event
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
            $this->state(GameState::class)->admin_user_ids->contains($this->admin_id),
            'Only admins can approve new players.'
        );
    }

    public function validate()
    {
        $this->assert(
            ! $this->state(GameState::class)->players()->map(fn ($p) => $p->user_id)->contains($this->user_id),
            'User is already in the game.'
        );

        $this->assert(
            GameState::load($this->game_id)->is_active,
            'The game is over.'
        );
    }

    public function fired()
    {
        PlayerJoinedGame::fire(
            user_id: $this->user_id,
            game_id: $this->game_id,
            player_id: $this->player_id,
        );
    }
}
