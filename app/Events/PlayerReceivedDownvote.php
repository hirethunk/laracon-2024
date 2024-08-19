<?php

namespace App\Events;

use App\Models\Player;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedDownvote extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $voter_id;

    public int $amount;

    public string $type;

    public function validate()
    {
        $this->assert(
            PlayerState::load($this->voter_id)->game_id === PlayerState::load($this->player_id)->game_id,
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

        $player->score -= $this->amount;
    }

    public function handle()
    {
        Player::find($this->player_id)->update([
            'score' => $this->states()->get('player')->score,
        ]);
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => $this->type,
                'amount' => 0 - $this->amount,
                'voter_name' => PlayerState::load($this->voter_id)->name,
                'score' => PlayerState::load($this->player_id)->score,
            ]
        );
    }
}
