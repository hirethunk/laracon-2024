<?php

namespace App\Events;

use App\Events\Concerns\HasAdmin;
use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasUser;
use App\Events\Concerns\RequiresActiveGame;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class AdminApprovedNewPlayer extends Event
{
	use RequiresActiveGame;
	use HasAdmin;
	use HasUser;
	use HasGame;
	
	#[StateId(PlayerState::class)]
	public ?int $player_id = null;

    public function validate()
    {
        $this->assert(
            ! $this->game()->players()->map(fn ($p) => $p->user_id)->contains($this->user_id),
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
}
