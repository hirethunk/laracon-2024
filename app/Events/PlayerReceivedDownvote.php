<?php

namespace App\Events;

use App\Models\Player;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\VerbsHistory\States\DTOs\HistoryComponentDto;
use Thunk\VerbsHistory\States\Interfaces\ExposesHistory;

class PlayerReceivedDownvote extends Event implements ExposesHistory
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $voter_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public int $amount;

    public string $type;

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
        return 'player_id = ' . $this->player_id .
            'voter_id = ' . $this->voter_id .
            'game_id = ' . $this->game_id .
            'amount = ' . $this->amount .
            'type = ' . $this->type;
    }
}
