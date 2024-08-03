<?php

namespace App\Events\Concerns;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

trait HasVoter
{
    #[StateId(PlayerState::class, 'voter')]
    public int $voter_id;

    public function validateHasVoter(): void
    {
        // If the event is not part of a game, skip game assertion
        if (! $game = $this->states()->firstOfType(GameState::class)) {
            return;
        }

        $this->assert(
            assertion: $game->isPlayer($this->voter_id),
            exception: 'Voter is not in the game.'
        );
    }

    protected function voter(): PlayerState
    {
        return $this->states()->get('voter');
    }
}
