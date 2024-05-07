<?php

namespace App\Events;

use App\Models\User;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\UserState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

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
            User::find($this->admin_id)->is_admin,
            'Only admins can approve new players.'
        );
    }

    public function validate()
    {
        $this->assert(
            ! $this->state(GameState::class)->players()->map(fn($p) => $p->user_id)->contains($this->user_id),
            'User is already in the game.'
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

    public function handle()
    {
        User::find($this->user_id)->update([
            'status' => 'approved',
            'player_id' => $this->player_id,
        ]);
    }
}
