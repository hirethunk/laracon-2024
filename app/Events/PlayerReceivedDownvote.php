<?php

namespace App\Events;

use App\Events\Concerns\AffectsVotes;
use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\HasVoter;
use App\Events\Concerns\RequiresActiveGame;
use App\States\GameState;
use Thunk\Verbs\Event;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedDownvote extends Event implements ExposesHistory
{
    use AffectsVotes;
    use HasGame;
    use HasPlayer;
    use HasVoter;
    use RequiresActiveGame;

    public int $amount = 1;

    public string $type;

    public function apply(GameState $game)
    {
        // Unused state forces a single trigger

        $this->applyDownvoteToPlayer(
            $this->player_id, $this->voter_id, $this->type, $this->amount
        );
    }

    public function handle()
    {
        $this->syncPlayerScore($this->player());
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => $this->type,
                'amount' => -$this->amount,
                'voter_name' => $this->voter()->name,
                'score' => $this->player()->score(),
            ]
        );
    }
}
