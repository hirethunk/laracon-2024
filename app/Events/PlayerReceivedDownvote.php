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

    public function validate(PlayerState $player, PlayerState $voter)
    {
        $this->assert(
            $voter->game_id === $player->game_id,
            'Voter and target are not in the same game.'
        );
    }

    public function applyToPlayer(PlayerState $player)
    {
        $player->score -= $this->amount;
    }

    public function handle(PlayerState $player)
    {
        Player::find($this->player_id)->update([
            'score' => $player->score,
        ]);
    }

    // @todo it would be sick to be able to dependency inject the states into this method
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
