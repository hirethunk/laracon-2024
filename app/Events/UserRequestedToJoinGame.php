<?php

namespace App\Events;

use App\Models\User;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class UserRequestedToJoinGame extends Event
{
    #[StateId(UserState::class)]
    public int $user_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public function validate()
    {
        $this->assert(
            $this->state(UserState::class)->status !== 'requested',
            'User has already requested to join this game.'
        );

        $this->assert(
            $this->state(UserState::class)->status !== 'approved',
            'User is already in the game.'
        );
    }

    public function applyToUser(UserState $state)
    {
        $state->status = 'requested';
    }

    public function applyToGame(GameState $state)
    {
        $state->user_ids_awaiting_approval->push($this->user_id);
    }

    public function handle()
    {
        User::find($this->user_id)->update([
            'status' => 'requested',
        ]);
    }
}
