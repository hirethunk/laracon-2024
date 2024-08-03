<?php

namespace App\Events;

use App\Events\Concerns\AffectsVotes;
use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\RequiresActiveGame;
use App\Models\Player;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerResigned extends Event
{
	use HasGame;
	use HasPlayer;
	use RequiresActiveGame;
	use AffectsVotes;

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

		$score = $player->score();

        if ($score > 0) {
			$this->applyUpvoteToPlayer(
				$this->beneficiary_id, $this->player_id, 'resignation', $score
            );
        }

        if ($score < 0) {
			$this->applyDownvoteToPlayer(
				$this->beneficiary_id, $this->player_id, 'resignation', abs($score)
            );
        }
    }

    public function handle()
    {
		Player::find($this->player_id)->update(['is_active' => false]);
    }
}
