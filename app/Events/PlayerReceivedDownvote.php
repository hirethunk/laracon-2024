<?php

namespace App\Events;

use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class PlayerReceivedDownvote extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $voter_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public function validate()
    {   
        $this->assert(
            $this->state(GameState::class)->player_ids->contains($this->player_id),
            'Player is not in the game.'
        );

        $this->assert(
            $this->state(GameState::class)->player_ids->contains($this->voter_id),
            'Voter is not in the game.'
        );
    }

    public function apply(PlayerState $state)
    {
        $state->downvotes[] = [
            'source' => $this->voter_id,
            'votes' => 1,
        ];
    }
}
