<?php

namespace App\Events;

use App\Models\Player;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedUpvote extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class, 'player')]
    public int $player_id;

    #[StateId(PlayerState::class, 'voter')]
    public int $voter_id;

    public int $amount;

    public string $type;

    public function validate()
    {
        $this->assert(
            $this->states()->get('voter')->game_id === $this->states()->get('player')->game_id,
            'Voter and target are not in the same game.'
        );
    }

    public function applyToPlayer(PlayerState $player)
    {
        if ($this->voter_id === $player->id) {
            // remove this once this PR is merged:
            // https://github.com/hirethunk/verbs/pull/155
            return;
        }

        $player->score += $this->amount;

        if ($this->type === 'buddy-system-reward') {
            $player->buddy_system_reward_received = true;
        }
    }

    public function handle()
    {
        Player::find($this->player_id)->update([
            'score' => $this->states()->get('player')->score,
        ]);
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        // @todo for some reason this is showing up as two items in the history, even tho scores are right

        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => $this->type,
                'amount' => $this->amount,
                'voter_name' => PlayerState::load($this->voter_id)->name,
                'score' => $this->states()->get('player')->score,
            ]
        );
    }
}
