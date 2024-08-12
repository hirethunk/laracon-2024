<?php

namespace App\Events;

use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerEnteredAllianceCode extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public int $ally_id;

    public int $alliance_code;

    public function authorize()
    {
        $this->assert(
            GameState::load($this->game_id)->player_ids->contains($this->player_id),
            'Player is not in the game.'
        );

        $this->assert(
            GameState::load($this->game_id)->ends_at > now(),
            'The game is over.'
        );
    }

    public function apply(PlayerState $state)
    {
        $code_is_correct = PlayerState::load($this->ally_id)
            ->code_to_give_to_ally === $this->alliance_code;

        if ($code_is_correct) {
            $state->has_connected_with_ally = true;

            $state->upvotes[] = [
                'source' => $this->ally_id,
                'votes' => 1,
                'type' => 'ally-connection',
            ];
        }
    }

    public function asHistory(): array|string|HistoryComponentDto
    {
        return new HistoryComponentDto(
            component: 'history.vote',
            props: [
                'type' => 'ally-connection',
                'amount' => 1,
                'voter_name' => PlayerState::load($this->ally_id)->name,
                'score' => $this->state(PlayerState::class)->score(),
            ]
        );
    }
}
