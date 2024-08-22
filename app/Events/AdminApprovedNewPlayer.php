<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class AdminApprovedNewPlayer extends Event
{
    #[StateId(UserState::class, 'admin')]
    public int $admin_id;

    #[StateId(UserState::class, 'user')]
    public int $user_id;

    #[StateId(GameState::class)]
    public int $game_id;

    #[StateId(PlayerState::class)]
    public ?int $player_id = null;

    public function authorize(UserState $admin)
    {
        $this->assert(
            $admin->is_admin_for->contains($this->game_id),
            'Only admins can approve new players.'
        );
    }

    public function validate(GameState $game)
    {
        $this->assert(
            ! $game->players()->map(fn ($p) => $p->user_id)->contains($this->user_id),
            'User is already in the game.'
        );

        $this->assert(
            $game->ends_at > now(),
            'The game is over.'
        );
    }

    public function fired(GameState $game)
    {
        if ($game->user_ids_approved->contains($this->user_id)) {
            return;
        }

        PlayerJoinedGame::fire(
            user_id: $this->user_id,
            game_id: $this->game_id,
            player_id: $this->player_id,
        );
    }
}
