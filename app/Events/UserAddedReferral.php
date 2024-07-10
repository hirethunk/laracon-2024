<?php

namespace App\Events;

use App\Models\User;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class UserAddedReferral extends Event
{
    #[StateId(UserState::class)]
    public int $user_id;

    #[StateId(GameState::class)]
    public int $game_id;

    #[StateId(PlayerState::class)]
    public int $referrer_player_id;

    public function validate()
    {
        $this->assert(
            $this->state(GameState::class)->player_ids->contains($this->referrer_player_id),
            'Referrer is not in game.'
        );
    }

    public function applyToUser(UserState $state)
    {
        $state->referrer_player_id = $this->referrer_player_id;
    }

    public function handle()
    {
        User::find($this->user_id)->update([
            'referrer_player_id' => $this->referrer_player_id,
        ]);
    }
}
