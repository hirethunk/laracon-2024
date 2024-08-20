<?php

namespace App\Events;

use App\Models\Player;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedDownvote extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class, 'player')]
    public int $player_id;

    #[StateId(PlayerState::class, 'voter')]
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
                'voter_name' => $this->states()->get('voter')->name,
                'score' => $this->states()->get('player')->score,
            ]
        );
    }
}
