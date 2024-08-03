<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasUser;
use App\Models\User;
use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserAddedReferral extends Event
{
	use HasGame;
	use HasUser;

    #[StateId(PlayerState::class)]
    public int $referrer_player_id;

    public function validate()
    {
        $this->assert(
            $this->game()->player_ids->contains($this->referrer_player_id),
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
