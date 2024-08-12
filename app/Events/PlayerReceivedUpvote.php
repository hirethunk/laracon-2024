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

    public int $game_id;

    public int $amount;

    public string $type;

    public function validate(PlayerState $player, PlayerState $voter)
    {
        $this->assert(
            $player->game_id === $this->game_id,
            'Player is not in the game.'
        );

        $this->assert(
            $voter->game_id === $this->game_id,
            'Player is not in the game.'
        );
    }

    public function applyToPlayer(PlayerState $state)
    {
        $state->upvotes[] = [
            'source' => $this->voter_id,
            'votes' => $this->amount,
            'type' => $this->type,
        ];
    }

    public function handle()
    {
        $player = Player::find($this->player_id);

        $player->score = $this->state(PlayerState::class)->score();

        $player->save();
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => $this->type,
                'amount' => $this->amount,
                'voter_name' => $this->state('voter')->name,
                'score' => $this->state('player')->score(),
            ]
        );
    }
}
