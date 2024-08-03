<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\RequiresActiveGame;
use App\Models\Player;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Event;

class PlayerResigned extends Event
{
	use HasGame;
	use HasPlayer;
	use RequiresActiveGame;

	#[StateId(PlayerState::class, 'beneficiary')]
    public int $beneficiary_id;

    public function validate()
    {
        $this->assert(
			assertion: $this->player()->is_active,
			exception: 'Player has already resigned.'
        );

        $this->assert(
			assertion: $this->game()->isPlayer($this->beneficiary_id),
			exception: 'Beneficiary is not in the game.'
        );

        $this->assert(
			assertion: $this->states()->get('beneficiary')->is_active,
			exception: 'Beneficiary has already resigned.'
        );
    }

    public function applyToPlayer(PlayerState $player)
    {
        $player->is_active = false;
        $player->beneficiary_id = $this->beneficiary_id;
    }

    public function fired()
    {
        $score = $this->player()->score();

        if ($score > 0) {
            PlayerReceivedUpvote::fire(
                player_id: $this->beneficiary_id,
                voter_id: $this->player_id,
                game_id: $this->game_id,
                type: 'resignation',
                amount: $score,
            );
        }

        if ($score < 0) {
            PlayerReceivedDownvote::fire(
                player_id: $this->beneficiary_id,
                voter_id: $this->player_id,
                game_id: $this->game_id,
                type: 'resignation',
                amount: -$score,
            );
        }
    }

    public function handle()
    {
        $player = Player::find($this->player_id);

        $player->is_active = false;

        $player->save();
    }
}
